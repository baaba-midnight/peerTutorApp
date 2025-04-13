<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/index.css" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0; 
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div id="particles-js"></div>
    <header class="bg-white py-4 shadow-md">

        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
            <div class="logo" style="width: 50px; height: 50px;">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <a href="#" class="text-xl font-semibold text-gray-800">PeerEd</a>
            </div>
            <nav class="hidden md:block">
                <ul class="flex space-x-6">
                    <li><a href="index.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Home</a></li>
                    <li class="text-gray-300">|</li> 
                    <li><a href="views/auth/login.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Login</a></li>
                     <li class="text-gray-300">|</li>
                    <li><a href="views/auth/register.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Sign Up</a></li>
                </ul>
            </nav>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 focus:outline-none focus:shadow-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div id="mobile-menu" class="hidden absolute top-16 right-0 bg-white shadow-lg rounded-md py-2 px-4 space-y-2">
                    <a href="views/auth/login.php" class="block text-gray-700 hover:text-blue-600 transition duration-300">Home</a>
                    <a href="views/auth/login.php" class="block text-gray-700 hover:text-blue-600 transition duration-300">Login</a>
                    <a href="views/auth/register.php" class="block text-gray-700 hover:text-blue-600 transition duration-300">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="text-center mb-12">
            <h1 class="text-3xl font-semibold text-gray-800 mb-4">Learn. Teach. Succeed.</h1>
            <p class="text-gray-600 mb-8">Connecting Students & Tutors Seamlessly.</p>
            <div class="flex justify-center space-x-4">
                <a href="views/auth/login.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-full transition duration-300">Get Started</a>
                <a href="views/auth/login.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-full transition duration-300">Find a Tutor</a>
                <a href="views/auth/login.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-full transition duration-300">Become a Tutor</a>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-8">Key Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-black rounded-lg shadow-md p-6 flex items-center justify-center">
                    <p class="text-white text-center">Search for expert tutors based on subject, rating, and availability.</p>
                </div>
                <div class="bg-black rounded-lg shadow-md p-6 flex items-center justify-center">
                    <p class="text-white text-center">Search for expert tutors based on subject, rating, and availability.</p>
                </div>
                <div class="bg-black rounded-lg shadow-md p-6 flex items-center justify-center">
                    <p class="text-white text-center">Search for expert tutors based on subject, rating, and availability.</p>
                </div>
                 <div class="bg-black rounded-lg shadow-md p-6 flex items-center justify-center">
                    <p class="text-white text-center">Search for expert tutors based on subject, rating, and availability.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-200 py-4 text-center">
        <p class="text-gray-600">© 2025 PeerEd. All Rights Reserved.</p>
    </footer>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#4a5568'  // Dark gray color for particles
                },
                shape: {
                    type: 'circle',
                    stroke: {
                        width: 0,
                        color: '#000000'
                    },
                    polygon: {
                        nb_sides: 5
                    },
                    image: {
                        src: 'img/github.svg',
                        width: 100,
                        height: 100
                    }
                },
                opacity: {
                    value: 0.5,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 40,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#4a5568', // Dark gray color for lines
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 6,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'repulse'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 400,
                        line_linked: {
                            opacity: 1
                        }
                    },
                    bubble: {
                        distance: 400,
                        size: 40,
                        duration: 2,
                        opacity: 0.8,
                        speed: 3
                    },
                    repulse: {
                        distance: 200,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    },
                    remove: {
                        particles_nb: 2
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
