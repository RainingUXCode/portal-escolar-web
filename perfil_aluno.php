<?php
require_once 'app/includes/funcoes.php';
require_once 'app/includes/conexao.php';

$aluno_data = null;
$noticias_fixas = obter_noticias_fixas();

proteger_pagina(['aluno']);

$id_aluno_logado = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT nome, email, matricula, data_nascimento, endereco FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_aluno_logado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $aluno_data = $result->fetch_assoc();
} else {
    redirecionar('area_aluno.php', 'erro', 'Seu perfil não foi encontrado. Tente novamente.');
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Colégio Esperança</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7), rgba(240, 245, 255, 0.7) 70%, rgba(255, 247, 240, 0.7));
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4 py-8 bg-gray-50">
    <?php echo exibir_flash_message(); ?>
    <div class="w-full max-w-2xl">
        <!-- Botão Voltar -->
        <a href="area_aluno.php" class="inline-flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-gray-900 mb-4 bg-white backdrop-blur-sm px-3 py-2 rounded-lg border border-gray-200">
            <img class="w-4 h-4" src="assets/img/voltar.svg">
            Voltar
        </a>

        <!-- Card do Perfil -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 md:p-8 w-full">

            <!-- Cabeçalho do Card  -->
            <div class="flex flex-row items-center gap-4 mb-8">
                <!-- Avatar -->
                <div id="avatar-container" class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xl font-semibold">TG</span>
                </div>
                <!-- Título e Subtítulo -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Meu Perfil</h2>
                    <p class="text-gray-500 mt-1">Gerencie suas informações pessoais</p>
                </div>
            </div>

            <!-- Informações do Perfil -->
            <div class="space-y-4">

                <!-- Email -->
                <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-4">
                    <img class="w-5 h-5" src="assets/img/email.svg">
                    <div>
                        <p class="text-sm text-gray-600">E-mail:</p>
                        <p class="text-base font-medium text-gray-900">
                            <?php echo htmlspecialchars($aluno_data['email']); ?>
                        </p>
                    </div>
                </div>

                <!-- Nome -->
                <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-4">
                    <img class="w-5 h-5" src="assets/img/nome.svg">
                    <div>
                        <p class="text-sm text-gray-600">Nome:</p>
                        <p class="text-base font-medium text-gray-900">
                            <?php echo htmlspecialchars($aluno_data['nome']); ?>
                        </p>
                    </div>
                </div>

                <!-- Matrícula -->
                <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-4">
                    <img class="w-5 h-5" src="assets/img/matricula.svg">
                    <div>
                        <p class="text-sm text-gray-600">Matricula:</p>
                        <p class="text-base font-medium text-gray-900">
                            <?php echo htmlspecialchars($aluno_data['matricula']); ?>
                        </p>
                    </div>
                </div>

                <!-- Data de Nascimento -->
                <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-4">
                    <img class="w-5 h-5" src="assets/img/data.svg">
                    <div>
                        <p class="text-sm text-gray-600">Data de Nascimento:</p>
                        <p class="text-base font-medium text-gray-900">
                            <?php echo htmlspecialchars($aluno_data['data_nascimento']); ?>
                        </p>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-4">
                    <img class="w-5 h-5" src="assets/img/endereco.svg">
                    <div>
                        <p class="text-sm text-gray-600">Endereço:</p>
                        <p class="text-base font-medium text-gray-900">
                            <?php echo htmlspecialchars($aluno_data['endereco']); ?>
                        </p>
                    </div>
                </div>

                <!-- Tipo de conta -->
                <div class="bg-gray-100 p-4 rounded-lg flex items-center gap-3">
                    <img class="w-5 h-5" src="assets/img/conta.svg">
                    <p class="text-md font-medium text-gray-600">Tipo de conta:</p>
                    <span class="text-xs bg-gray-900 text-white font-semibold px-2 py-1 rounded-md">Estudante</span>
                </div>
            </div>

            <!-- Botão Editar Perfil -->
            <div class="mt-8">
                <button type="button" id="edit-profile-btn" class="bg-gray-900 text-white py-2.5 px-6 rounded-lg font-semibold hover:bg-gray-800 transition duration-300">
                    Editar Perfil
                </button>
            </div>

        </div>
    </div>

    <div class="w-full max-w-2xl mt-10">
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 md:p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-regular text-gray-900">Últimas Notícias</h2>
                <a href="noticias.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200 rounded">Ver todas</a>
            </div>
            <div class="space-y-6">
                <?php echo render_noticias_fixas_cards($noticias_fixas); ?>
            </div>
        </div>
    </div>

    <!-- Modal de Seleção de Avatar -->
    <div id="avatar-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <!-- Cabeçalho do Modal -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Escolha seu avatar</h3>
                <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600">
                    <img class="w-4 h-4" src="assets/img/fechar.png">
                </button>
            </div>
            <!-- Grelha de Avatares -->
            <div id="avatar-selection-grid" class="p-6 grid grid-cols-4 gap-4">
                <!-- Avatares -->
                <img src="assets/perfil/Cachorro.jpg" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Gato.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Flores.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Esporte.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garota1.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garota2.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garota3.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garota4.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garoto1.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garoto2.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garoto3.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
                <img src="assets/perfil/Garoto4.png" class="avatar-choice w-full rounded-full cursor-pointer hover:opacity-75 transition-all duration-200">
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 text-right">
                <button id="save-avatar-btn" class="bg-gray-900 text-white py-2 px-5 rounded-lg font-semibold hover:bg-gray-800 transition duration-300">
                    Salvar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editProfileBtn = document.getElementById('edit-profile-btn');
            const avatarModal = document.getElementById('avatar-modal');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const avatarSelectionGrid = document.getElementById('avatar-selection-grid');
            const avatarContainer = document.getElementById('avatar-container');
            const saveAvatarBtn = document.getElementById('save-avatar-btn'); // Botão Salvar

            let tempSelectedAvatarSrc = null;

            function updateAvatar(src) {
                if (avatarContainer) {
                    avatarContainer.innerHTML = '';
                    const newImg = document.createElement('img');
                    newImg.src = src;
                    newImg.className = 'w-full h-full object-cover rounded-full';
                    avatarContainer.appendChild(newImg);
                }
            }

            const savedAvatar = localStorage.getItem('userAvatar');
            if (savedAvatar) {
                updateAvatar(savedAvatar);
            }

            if (editProfileBtn && avatarModal && closeModalBtn && avatarSelectionGrid && avatarContainer && saveAvatarBtn) {


                editProfileBtn.addEventListener('click', () => {
                    avatarModal.classList.remove('hidden');
                    tempSelectedAvatarSrc = null;
                    avatarSelectionGrid.querySelectorAll('.avatar-choice').forEach(img => {
                        img.classList.remove('ring-4', 'ring-blue-500');
                    });
                });
                closeModalBtn.addEventListener('click', () => {
                    avatarModal.classList.add('hidden');
                });


                avatarSelectionGrid.addEventListener('click', (event) => {
                    const clickedImg = event.target.closest('.avatar-choice');
                    if (clickedImg) {
                        avatarSelectionGrid.querySelectorAll('.avatar-choice').forEach(img => {
                            img.classList.remove('ring-4', 'ring-blue-500');
                        });

                        clickedImg.classList.add('ring-4', 'ring-blue-500');
                        tempSelectedAvatarSrc = clickedImg.src;
                    }
                });

                saveAvatarBtn.addEventListener('click', () => {
                    if (tempSelectedAvatarSrc) {
                        updateAvatar(tempSelectedAvatarSrc);
                        localStorage.setItem('userAvatar', tempSelectedAvatarSrc);
                        avatarModal.classList.add('hidden');
                    } else {
                        avatarModal.classList.add('hidden');
                    }
                });

            } else {
                console.error("Erro: Não foi possível encontrar todos os elementos do modal de avatar.");
            }
        });
    </script>
</body>

</html>