<#
PowerShell script to run tests for portal-simple
Usage:
  .\run-tests.ps1           # runs PHPUnit by default
  .\run-tests.ps1 -All      # runs PHPUnit then Playwright
  .\run-tests.ps1 -Playwright # runs Playwright only
#>
param(
    [switch]$All,
    [switch]$Playwright
)

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $projectRoot

Write-Host "Running tests in $projectRoot"

function Ensure-ComposerOrPhar {
    # Ensure PHP CLI available
    $phpCmdObj = Get-Command php -ErrorAction SilentlyContinue
    $phpCmd = $null
    if ($phpCmdObj) { $phpCmd = $phpCmdObj.Source }
    if (-not $phpCmd) {
        $candidate = 'C:\\xampp\\php\\php.exe'
        if (Test-Path $candidate) { $phpCmd = $candidate }
    }
    if (-not $phpCmd) {
        Write-Error "PHP CLI not found. Install PHP or ensure php.exe is in PATH (or adjust run-tests.ps1)."
        exit 1
    }

    # If composer present use it, otherwise download phpunit PHAR if needed
    $composerObj = Get-Command composer -ErrorAction SilentlyContinue
    $composerCmd = $null
    if ($composerObj) { $composerCmd = $composerObj.Source }
    if ($composerCmd) {
        Write-Host "Installing PHP dev dependencies with Composer..."
        & $composerCmd install --no-interaction
        return $phpCmd
    }

    # No composer: ensure phpunit.phar exists
    $phpunitPhar = Join-Path $projectRoot 'phpunit.phar'
    if (-not (Test-Path $phpunitPhar)) {
        Write-Host "Composer not found - downloading phpunit PHAR..."
        # Try to download via PowerShell's Invoke-WebRequest if available
        $url = 'https://phar.phpunit.de/phpunit-9.6.phar'
        try {
            if (Get-Command Invoke-WebRequest -ErrorAction SilentlyContinue) {
                Invoke-WebRequest -Uri $url -OutFile $phpunitPhar -UseBasicParsing
            } else {
                # Fallback to php -r if Invoke-WebRequest is not available
                $phpArg = "copy('$url','phpunit.phar');"
                & $phpCmd -r $phpArg
            }
        } catch {
            Write-Error "Failed to download phpunit.phar: $_"
            exit 1
        }
        if (-not (Test-Path $phpunitPhar)) {
            Write-Error "Failed to download phpunit.phar."
            exit 1
        }
    }
    return $phpCmd
}

$phpCli = Ensure-ComposerOrPhar

function Run-PhpUnit {
    # Prefer vendor phpunit if available
    $vendorPhpunit = "$projectRoot\vendor\bin\phpunit"
    $vendorBat = "$projectRoot\vendor\bin\phpunit.bat"
    if ((Test-Path $vendorPhpunit) -or (Test-Path $vendorBat)) {
        $cmd = if (Test-Path $vendorBat) { $vendorBat } else { $vendorPhpunit }
        Write-Host "Running PHPUnit from vendor..."
        & $cmd -c phpunit.xml
        return
    }

    # Fallback to phpunit.phar downloaded earlier
    $phpunitPhar = Join-Path $projectRoot 'phpunit.phar'
    if (Test-Path $phpunitPhar) {
        Write-Host "Running PHPUnit PHAR..."
        & $phpCli $phpunitPhar -c phpunit.xml
        return
    }

    Write-Error "No phpunit available (composer vendor or phpunit.phar)."
}

function Run-Playwright {
    # Ensure node deps
    if (-not (Test-Path "$projectRoot\node_modules")) {
        Write-Host "Installing Node dependencies..."
        & npm install
        & npx playwright install
    }

    Write-Host "Running Playwright tests..."
    & npx playwright test
}

if ($Playwright) {
    Run-Playwright
} else {
    Run-PhpUnit
    if ($All) { Run-Playwright }
}
