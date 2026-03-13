<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Class 1 - Inspire Tech Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../style.css" />

</head>
<body class="ai-course-page">

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar">
                <h5>Ai_course Map</h5>
                <a href="1st Ai intro and Space Search.php" class="class-item active"><i class="fas fa-play-circle"></i> 01. Intro & State Space</a>
                <a href="2nd History and Types of AI.php" class="class-item"><i class="fas fa-lock"></i> 02. History and Type Of AI</a>
                <a href="3rd Prompt and its engineering.php" class="class-item"><i class="fas fa-lock"></i> 03. Prompts and its Engineering</a>


            </div>
        </div>

        <div class="col-lg-9 col-md-12">
            
            <div class="content-card animate__animated animate__fadeInUp">
                <h2 class="topic-header"><i class="fas fa-list-ul"></i> 1. AI Syllabus & Discussion</h2>
                <p>In this first module at <strong>Inspire Tech Computer Academy</strong>, we establish the foundation. AI is not just about writing code; it's about building a digital brain. Our syllabus follows a logical progression:</p>
                <ul>
                    <li><strong>Foundational Logic:</strong> Understanding how machines "think" using Boolean logic and probability.</li>
                    <li><strong>Problem Solving:</strong> Learning how to represent real-world problems (like chess or navigation) in a way that AI can understand.</li>
                    <li><strong>The Tools:</strong> Introduction to Python, NumPy, and TensorFlow.</li>
                </ul>
                <div class="video-box">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/uB3i-qV6VdM?si=bDJtFcCLpiCV9okQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>

            <div class="content-card animate__animated animate__fadeInUp">
                <h2 class="topic-header"><i class="fas fa-rocket"></i> 2. The Future of AI</h2>
                <p>The "AI Revolution" is currently termed the <strong>Fourth Industrial Revolution</strong>. Unlike previous revolutions that replaced manual labor, AI is augmenting (and sometimes replacing) cognitive labor.</p>
                <p><strong>Key Trends:</strong></p>
                <ul>
                    <li><strong>Hyper-Personalization:</strong> AI that knows your health needs before you do.</li>
                    <li><strong>Quantum AI:</strong> Using quantum computers to train models in seconds that currently take months.</li>
                    <li><strong>Ethics & Safety:</strong> As AI grows, the world needs experts to ensure it remains beneficial for humanity.</li>
                </ul>
                
                <div class="video-box">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/gm5DcqzZ3f4?si=GUpYRw56Wlf-FkBS" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>

            <div class="content-card animate__animated animate__fadeInUp">
                <h2 class="topic-header"><i class="fas fa-brain"></i> 3. What is Artificial Intelligence?</h2>
                <p>At its simplest, AI is the science of making machines perform tasks that typically require human intelligence. This includes things like <strong>visual perception, speech recognition, and decision-making</strong>.</p>
                <div class="alert alert-info">
                    <strong>Simple Example:</strong> Think of a "Smart Thermostat." A regular one just turns off at a temperature. An <strong>AI-powered</strong> one learns your schedule, knows when you are coming home, and adjusts the temperature to save you money automatically.
                </div>
                <div class="video-box">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/ug9QYEfzN48?si=oUZ_JG1SB3IlpYnV" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>    
                </div>
            </div>

            <div class="content-card animate__animated animate__fadeInUp">
                <h2 class="topic-header"><i class="fas fa-project-diagram"></i> 4. What is State Space Search?</h2>
                <p>State Space Search is the mathematical heart of AI problem solving. It represents every possible "state" a problem can be in as a node in a giant map (or tree).</p>
                <p>To solve a problem using State Space, we need four things:</p>
                <ol>
                    <li><strong>Initial State:</strong> The starting point (e.g., the mixed-up Rubik's Cube).</li>
                    <li><strong>Actions (Operators):</strong> The moves we are allowed to make.</li>
                    <li><strong>Goal Test:</strong> A way to check if we have solved the problem.</li>
                    <li><strong>Path Cost:</strong> The effort (time/money) spent to get to the solution.</li>
                </ol>
                
                <div class="video-box">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/E5jVBqe59EE?si=EwOndruvmJVDFoLy" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>   
                </div>
            </div>

            <div id="quiz-box" class="animate__animated animate__pulse">
                <h3 class="text-info mb-4"><i class="fas fa-terminal"></i> Knowledge Check</h3>
                <div id="quiz-content">
                    <h5 id="question-text">Q1: In AI, what is the 'Initial State'?</h5>
                    <div id="options-container" class="mt-3">
                        <div class="quiz-option" onclick="processQuiz(false)">The solution to the problem</div>
                        <div class="quiz-option" onclick="processQuiz(true)">The starting point or current situation</div>
                        <div class="quiz-option" onclick="processQuiz(false)">The rules of the game</div>
                    </div>
                    <div id="feedback" class="feedback-area mt-3 fw-bold"></div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-5">
                <button class="btn btn-outline-secondary btn-lg rounded-pill px-4" onclick="alert('You are on the first page!')">
                    <i class="fas fa-chevron-left me-2"></i> Previous
                </button>
                <button class="btn btn-primary btn-lg rounded-pill px-5 shadow" onclick="location.reload();">
                    Next Lesson <i class="fas fa-chevron-right ms-2"></i>
                </button>
            </div>

        </div>
    </div>
</div>

<footer id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-brand mb-3 text-uppercase">Inspire Tech</div>
                <p>Empowering the youth of Nowshera with cutting-edge IT skills. Join the digital revolution today.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5>Contact Head Office</h5>
                <p><i class="fas fa-map-marker-alt me-2"></i> Khattak Building, Nowshera Cantt</p>
                <p><i class="fas fa-phone me-2"></i> 03462345453</p>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center small opacity-75">
            © 2026 Inspire Tech Computer Academy. All Rights Reserved.
        </div>
    </div>
</footer>

<script src="/support-hub.js">

    let step = 1;

    function processQuiz(isCorrect) {
        const feedback = document.getElementById('feedback');
        const questionText = document.getElementById('question-text');
        const optionsContainer = document.getElementById('options-container');

        if (isCorrect) {
            feedback.innerHTML = "<span class='text-success animate__animated animate__bounceIn'>✔️ Correct! Well done. Moving to next question...</span>";
            
            setTimeout(() => {
                if(step === 1) {
                    step = 2;
                    questionText.innerText = "Q2: Which type of AI is designed for one specific task (like Chess or Weather prediction)?";
                    optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(true)">Narrow AI (ANI)</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Super AI (ASI)</div>
                        <div class="quiz-option" onclick="processQuiz(false)">General AI (AGI)</div>
                    `;
                    feedback.innerText = "";
                } else {
                    feedback.innerHTML = "<span class='text-info'>🎉 Lesson Complete! You are ready for Class 2.</span>";
                    optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Mastery Achieved! You answered all questions correctly.</div>`;
                }
            }, 1500);
        } else {
            feedback.innerHTML = "<span class='text-danger animate__animated animate__shakeX'>❌ Wrong Answer! Hint: The initial state is where we 'begin' the journey. Try again.</span>";
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



