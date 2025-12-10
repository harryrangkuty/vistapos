<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/images/simaris_favicon.webp" type="image/x-icon">
    <title>VISTA POS | Versatile Integrated Sales & Transaction Assistant Point of Sale</title>

    {{-- @vite(['resources/js/frontend.js']) --}}
    @vite(['resources/sass/frontend.scss'])

    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(1.1);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            60% {
                transform: scale(1.05);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .fade-in {
            animation: fadeIn 1.2s ease forwards;
        }

        .float {
            animation: float 3s ease-in-out infinite;
        }

        .bounce-in {
            animation: bounceIn 0.6s ease;
        }

        /* Loader */
        .loader {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
            background-image: linear-gradient(180deg, #000000 40%, #1a0026);
            z-index: 100;
            transition: opacity 0.4s ease;
        }

        .loader-circle {
            width: 64px;
            height: 64px;
            border: 5px solid #7c3aed;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Modal Background */
        .modal-bg {
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 2s ease-in-out infinite;
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 25s linear infinite;
        }

        .glow {
            text-shadow: 0 0 10px rgba(167, 139, 250, 0.8),
                0 0 20px rgba(167, 139, 250, 0.6),
                0 0 40px rgba(167, 139, 250, 0.4);
        }

        /* Animasi */
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.7;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 5s ease-in-out infinite;
        }

        @keyframes pop {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            60% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-pop {
            animation: pop 0.6s ease-out;
        }

        .glow-text {
            text-shadow:
                0 0 10px rgba(168, 85, 247, 0.6),
                0 0 20px rgba(139, 92, 246, 0.4);
        }

        @keyframes spin-medium {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin-medium 1s linear infinite;
        }
    </style>
</head>

<body class="overflow-hidden font-sans bg-black text-white">
    {{-- LOADER --}}
    <div id="loader" class="loader">
        <div class="loader-circle"></div>
    </div>
    {{-- MAIN CONTENT --}}
    <div class="relative h-screen w-screen overflow-hidden">
        <img src="/images/vista_pos_wallpaper.webp" alt="Background"
            class="absolute inset-0 w-full h-full object-cover brightness-100">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-black opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/60 via-violet-800/50 to-indigo-900/40">
        </div>
        <main id="main-content"
            class="h-full flex flex-col items-center justify-center text-center px-4 relative overflow-hidden animate-fade-in">
            <div class="absolute top-10 left-10 text-violet-400/30 text-6xl animate-pulse glow">⚙️</div>
            <div class="absolute bottom-16 right-12 text-indigo-400/25 text-5xl animate-bounce glow">💡</div>
            <div class="absolute top-1/3 right-1/4 text-purple-500/20 text-7xl animate-spin-slow glow">🌐</div>
            <!-- card -->
            <div
                class="relative bg-white/60 backdrsop-blur-xl border border-white/40 rounded-3xl shadow-[0_0_50px_rgba(168,85,247,0.3)] px-28 py-12 flex flex-col items-center hover:shadow-[0_0_40px_rgba(139,92,246,0.5)] transition-all duration-700 ease-in-out">
                <img src="/images/vista_logo.webp" alt="Logo"
                    class="w-40 h-40 mb-6 float drop-shadow-[0_0_15px_rgba(168,85,247,0.4)] animate-float">
                <div class="flex gap-x-2 text-5xl font-extrabold tracking-wide drop-shadow-md">
                    <h1 class="text-blue-900">VISTA</h1>
                    <span class="text-yellow-500">POS</span>
                </div>
                <div class="mt-2 text-lg italic tracking-wide">
                    <span class="text-blue-900">Versatile Integrated Sales & Transaction Assistant</span>
                    <span class="text-yellow-500 text-xl bg-blue-900 py-1 px-4 rounded-full">Point of Sale</span>
                </div>
                <button onclick="openModal()"
                    class="relative mt-10 px-12 py-3 font-semibold text-white tracking-wide rounded-full 
                        bg-gradient-to-r from-violet-700 via-purple-600 to-violet-800 
                        hover:from-violet-600 hover:to-purple-700 
                        shadow-[0_0_25px_rgba(139,92,246,0.4)] 
                        hover:shadow-[0_0_35px_rgba(139,92,246,0.6)] 
                        transition-all duration-500 ease-in-out 
                        hover:scale-[1.05] active:scale-95 group overflow-hidden">

                    <span class="relative z-10">Login ke Sistem</span>

                    <!-- Cahaya halus melintasi tombol -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent 
                            translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-out">
                    </div>
                </button>
                <p class="mt-8 text-sm text-blue-900">
                    © 2025 Designed and Programmed by Harry Rangkuti, A.Md.Kom
                </p>
            </div>
        </main>
        {{-- MODAL LOGIN --}}
        <div id="loginModal"
            class="fixed inset-0 flex items-center justify-center modal-bg z-50 opacity-0 scale-95 pointer-events-none transition-all duration-300 ease-in-out">

            <div
                class="relative bg-gradient-to-br from-white/10 via-violet-900/20 to-purple-900/10 backdrop-blur-2xl border border-violet-400/30 shadow-[0_0_50px_rgba(168,85,247,0.3)] rounded-3xl p-10 w-full max-w-md text-white transform transition-all duration-300 ease-in-out hover:shadow-[0_0_60px_rgba(139,92,246,0.5)]">

                <!-- Glow layer -->
                <div
                    class="absolute inset-0 rounded-3xl bg-gradient-to-br from-violet-500/10 to-indigo-800/10 blur-2xl">
                </div>

                <div class="relative z-10">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-violet-300 tracking-wide glow-text">Login ke VISTA POS</h2>
                        <button onclick="closeModal()"
                            class="text-gray-400 text-3xl font-bold hover:text-white hover:scale-110 transition-transform ease-in-out">&times;</button>
                    </div>

                    <!-- Form -->
                    <form id="login-form" class="space-y-5">
                        @csrf

                        <!-- Identifier -->
                        <div class="relative group">
                            <span
                                class="absolute left-3 top-3.5 text-gray-400 group-focus-within:text-violet-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.5 19.5a7.5 7.5 0 0 1 15 0v.75H4.5v-.75z" />
                                </svg>
                            </span>
                            <input type="text" name="identifier" placeholder="Masukkan NIP"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/10 text-white placeholder-gray-400 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/60 outline-none transition-all duration-300"
                                required>
                            <div id="identifier-error" class="text-red-400 text-sm mt-1"></div>
                        </div>

                        <!-- Password -->
                        <div class="relative group">
                            <span
                                class="absolute left-3 top-3.5 text-gray-400 group-focus-within:text-violet-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V7.5a4.5 4.5 0 1 0-9 0v3h9zM5.25 10.5h13.5a1.5 1.5 0 0 1 1.5 1.5v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5z" />
                                </svg>
                            </span>
                            <input type="password" name="password" id="password-field" placeholder="Password"
                                class="w-full pl-10 pr-10 py-3 rounded-xl bg-white/10 text-white placeholder-gray-400 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/60 outline-none transition-all duration-300"
                                required>
                            <span onclick="togglePassword()" id="toggle-password-icon"
                                class="absolute right-3 top-3.5 cursor-pointer text-gray-400 hover:text-violet-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.314 4.5 12 4.5c4.686 0 8.577 3.01 9.964 7.178.07.208.07.436 0 .644C20.577 16.49 16.686 19.5 12 19.5c-4.686 0-8.577-3.01-9.964-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                </svg>
                            </span>
                            <div id="password-error" class="text-red-400 text-sm mt-1"></div>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="relative w-full py-3 mt-2 bg-gradient-to-r from-violet-700 via-purple-600 to-violet-800 rounded-xl font-semibold tracking-wide hover:from-violet-600 hover:to-purple-700 shadow-[0_0_20px_rgba(139,92,246,0.4)] hover:shadow-[0_0_35px_rgba(139,92,246,0.6)] transition-all duration-300 ease-in-out hover:scale-[1.03]">
                            <span class="relative z-10">Login</span>
                            <div
                                class="absolute inset-0 rounded-xl bg-gradient-to-r from-purple-400/10 via-transparent to-transparent blur-xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </button>
                    </form>

                    <!-- Footer -->
                    <p class="mt-6 text-xs text-gray-400 text-center italic">
                        © 2025 Designed and Programmed by Harry Rangkuti, A.Md.Kom
                    </p>
                </div>
            </div>
        </div>
        {{-- MODAL ALERT --}}
        <div id="alertModal"
            class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-[6px] z-[100] hidden transition-all duration-500">
            <div id="alertBox"
                class="relative bg-gradient-to-br from-[#2a0047]/90 via-[#3b0066]/80 to-[#2e1065]/90 border border-violet-500/40 shadow-[0_0_25px_rgba(139,92,246,0.4)] rounded-3xl text-white text-center p-10 w-[90%] max-w-sm scale-90 opacity-0 transition-all duration-500 ease-out">
                <div
                    class="absolute inset-0 rounded-3xl bg-gradient-to-br from-violet-600/20 to-indigo-800/10 blur-2xl animate-pulse-slow pointer-events-none">
                </div>
                <div id="alertIcon"
                    class="text-5xl mb-4 animate-pop text-violet-400 drop-shadow-[0_0_15px_rgba(168,85,247,0.8)]">
                    ⚡
                </div>
                <h3 id="alertTitle" class="text-2xl font-bold mb-3 tracking-wide text-violet-200 glow-text">Judul
                    Alert
                </h3>
                <p id="alertMessage" class="text-base text-gray-200 leading-relaxed"></p>
                <button onclick="closeAlert()"
                    class="mt-8 bg-violet-700/90 hover:bg-violet-800/90 px-8 py-3 rounded-full font-semibold tracking-wide text-white shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.7)] transition-all duration-300 hover:scale-105">
                    OK
                </button>
            </div>
        </div>

        <script>
            // Loader fade-out setelah JS siap
            document.addEventListener('DOMContentLoaded', () => {
                const loader = document.getElementById('loader');
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.remove();
                        document.getElementById('main-content').style.opacity = '1';
                    }, 400);
                }, 300);
            });

            window.addEventListener('load', () => {
                const main = document.getElementById('main-content');
                main.classList.remove('invisible');
                main.classList.add('opacity-100', 'scale-100');
            });

            // Modal control
            function openModal() {
                const modal = document.getElementById('loginModal');
                modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                modal.classList.add('opacity-100', 'scale-100');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                const modal = document.getElementById('loginModal');
                modal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                modal.classList.remove('opacity-100', 'scale-100');
                document.body.classList.remove('overflow-hidden');
            }

            function togglePassword() {
                const input = document.getElementById('password-field');
                input.type = input.type === 'password' ? 'text' : 'password';
            }

            // Modal alert custom
            function showAlert(title, message, type = 'info') {
                const modal = document.getElementById('alertModal');
                const box = document.getElementById('alertBox');
                const titleEl = document.getElementById('alertTitle');
                const msgEl = document.getElementById('alertMessage');
                const okBtn = box.querySelector('button');
                const iconEl = document.getElementById('alertIcon');

                // warna sesuai tipe
                const colors = {
                    success: 'text-green-400',
                    error: 'text-red-400',
                    info: 'text-blue-400',
                    warning: 'text-yellow-400'
                };

                // Reset icon dulu
                iconEl.innerHTML = '⚡';
                iconEl.classList.remove('animate-spin');

                // Setup konten
                titleEl.className = `text-2xl font-bold mb-3 tracking-wide glow-text ${colors[type] || ''}`;
                titleEl.textContent = title;
                msgEl.textContent = message;
                okBtn.classList.remove('hidden');

                // Tampilkan modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    box.classList.remove('scale-90', 'opacity-0');
                    box.classList.add('scale-100', 'opacity-100');
                }, 10);

                // Jika success -> auto close & redirect
                if (type === 'success') {
                    okBtn.classList.add('hidden');

                    // Ganti icon ke spinner
                    iconEl.innerHTML = '⟳';
                    iconEl.classList.add('animate-spin', 'text-violet-300');

                    // Tambahkan teks bawah
                    const loadingMsg = document.createElement('p');
                    loadingMsg.id = 'loadingMsg';
                    loadingMsg.className = 'mt-4 text-sm text-gray-300 italic';
                    loadingMsg.textContent = 'Mengalihkan ke dashboard...';
                    box.appendChild(loadingMsg);

                    // Optional countdown
                    let countdown = 3;
                    const interval = setInterval(() => {
                        loadingMsg.textContent = `Mengalihkan ke dashboard... (${countdown})`;
                        countdown--;
                        if (countdown < 0) {
                            clearInterval(interval);
                        }
                    }, 1000);

                    // Tutup alert & redirect
                    setTimeout(() => {
                        closeAlert();
                        window.location.href = "{{ route('dashboard') }}";
                    }, 4000);
                } else {
                    // Pastikan loadingMsg tidak tertinggal
                    const oldMsg = document.getElementById('loadingMsg');
                    if (oldMsg) oldMsg.remove();
                }
            }

            function closeAlert() {
                const modal = document.getElementById('alertModal');
                const box = document.getElementById('alertBox');
                box.classList.add('scale-90', 'opacity-0');

                const onTransitionEnd = () => {
                    modal.classList.add('hidden');
                    // bersihkan loadingMsg biar gak duplikat nanti
                    const oldMsg = document.getElementById('loadingMsg');
                    if (oldMsg) oldMsg.remove();
                    box.removeEventListener('transitionend', onTransitionEnd);
                };
                box.addEventListener('transitionend', onTransitionEnd);
            }


            // Login AJAX
            document.getElementById('login-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                document.getElementById('identifier-error').innerText = '';
                document.getElementById('password-error').innerText = '';

                const form = e.target;
                const formData = new FormData(form);

                try {
                    const response = await fetch('/login', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const result = await response.json();
                        closeModal();
                        showAlert('Login Berhasil', result.message || 'Selamat datang di VISTA POS', 'success');
                        setTimeout(() => window.location.href = "{{ route('dashboard') }}", 2000);
                    } else if (response.status === 401) {
                        const result = await response.json();
                        document.getElementById('identifier-error').innerText = result.message || 'Login gagal.';
                        showAlert('Login Gagal', 'Periksa kembali NIP dan password Anda.', 'error');
                    } else {
                        showAlert('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                } catch (error) {
                    showAlert('Error', 'Tidak dapat terhubung ke server.', 'error');
                }
            });

            // Hapus error text realtime
            document.querySelectorAll('#login-form input').forEach(input => {
                input.addEventListener('input', function() {
                    const errorElement = document.getElementById(`${this.name}-error`);
                    if (errorElement) errorElement.innerText = '';
                });
            });
        </script>

</body>

</html>
