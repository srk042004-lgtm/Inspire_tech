<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Class 7 - Modules & OOP | Inspire Tech Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../style.css" />
</head>
<body class="ai-course-page ai-course-page">

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar">
                <h5>Ai_course Map</h5>
               
                <a href="6th data structure and functions.php" class="class-item"><i class="fas fa-lock"></i> 06. Data Structure and Functions</a>
                <a href="7th Module and OOP option.php" class="class-item active"><i class="fas fa-lock"></i> 07. Module and OOP Options</a>
                <a href="8th mathematics and its use.php" class="class-item"><i class="fas fa-lock"></i> 08. Mathematics And its use in AI </a>
                


            </div>
        </div>



        <div class="col-lg-9">
            
            <div class="content-card animate__animated animate__fadeIn">
                <h2 class="topic-header">Class 18: Python Modules & Packages</h2>
                <p>In Python, a <strong>Module</strong> is simply a file containing Python code that you can reuse in other programs to keep your projects organized and clean. Instead of writing every single function from scratch, you can <code>import</code> modules like <code>math</code> for advanced calculations or <code>random</code> for generating numbers. This modular approach allows developers to break down huge AI systems into smaller, manageable files, which is a standard practice in the software industry. You can also create your own modules by saving a script and importing it into a new project, which promotes code reusability and efficiency. Packages are collections of these modules grouped together to provide even more functionality. At Inspire Tech, we teach you that mastering imports is the key to accessing the thousands of powerful tools available in the Python community today.</p>
                
                <pre><code>import math
print(math.sqrt(16)) # Output: 4.0</code></pre>

                <div class="video-box">
                    <iframe src="https://www.youtube.com/embed/CqvZ3vGoGs0" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div class="content-card">
                <h2 class="topic-header">Class 19: Using PIP (The Package Installer)</h2>
                <p><strong>PIP</strong> is the standard package manager for Python, allowing you to install and manage additional libraries that are not included in the basic Python installation. It connects your computer to the Python Package Index (PyPI), a massive repository where developers share millions of open-source projects. For AI students, PIP is the most important tool because it is used to install heavy-duty libraries like <strong>NumPy</strong>, <strong>Pandas</strong>, and <strong>TensorFlow</strong>. By typing a simple command like <code>pip install library_name</code>, you can add world-class AI capabilities to your machine in seconds. PIP also handles "dependencies," ensuring that all the supporting code required for a library to work is automatically downloaded. Understanding how to manage your environment with PIP is what separates a student from a professional developer ready for real-world projects.</p>
                
                

                <pre><code># Command to run in Terminal/CMD
pip install requests
pip list</code></pre>

                <div class="video-box">
                    <iframe src="https://www.youtube.com/embed/U2znX6yV-u0" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div class="content-card">
                <h2 class="topic-header">Class 20: Introduction to Object-Oriented Programming</h2>
                <p><strong>Object-Oriented Programming (OOP)</strong> is a programming paradigm based on the concept of "objects," which can contain data and code to manipulate that data. Think of it as creating a blueprint (a Class) for a car, and then creating many actual cars (Objects) from that same blueprint. This approach allows us to model real-world scenarios in our code, making complex AI systems much easier to design and maintain. Instead of writing loose functions, we group related data and behaviors into "Classes," which acts as a protective container. In AI, we often use OOP to define a "Model" class that has properties like accuracy and methods like "train" or "predict." Mastering OOP is essential for any advanced Python career because it makes your code scalable and easy for other developers to understand and extend.</p>
                
                

                <div class="video-box">
                    <iframe src="https://www.youtube.com/embed/JeznW_7DlB0" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div class="content-card">
                <h2 class="topic-header">Class 21: Creating Classes and Objects</h2>
                <p>To create a class in Python, we use the <code>class</code> keyword followed by a name, and we use a special method called <code>__init__</code> to initialize the object's properties. These properties are variables that belong to the object, such as the name or age of a student. We also define "Methods," which are functions inside a class that allow the object to perform actions. When we create an "Instance" of a class, we are essentially bringing that blueprint to life with specific data. For example, a "Robot" class could have an instance named "Robo1" with a property like "Battery Level" and a method called "Walk." This structure is the backbone of professional software development and is used in almost every modern AI library you will encounter. At Inspire Tech, we focus on hands-on practice to help you understand how these logical structures connect to build intelligent, autonomous machines.</p>
                
                <pre><code>class Student:
    def __init__(self, name, grade):
        self.name = name
        self.grade = grade

s1 = Student("Ali", "A")
print(s1.name)</code></pre>

                <div class="video-box">
                    <iframe src="https://www.youtube.com/embed/ZDa-Z5JzLYM" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div id="quiz-box">
                <h3 class="text-info mb-4"><i class="fas fa-microchip me-2"></i> Advanced Logic Test</h3>
                <div id="quiz-content">
                    <h5 id="question-text">Q1: Which command is used to install an external library like NumPy?</h5>
                    <div id="options-container" class="mt-3">
                        <div class="quiz-option" onclick="processQuiz(false)">import numpy</div>
                        <div class="quiz-option" onclick="processQuiz(true)">pip install numpy</div>
                        <div class="quiz-option" onclick="processQuiz(false)">get numpy</div>
                    </div>
                    <div id="feedback" class="mt-3 fw-bold"></div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-5 mb-5">
                <button class="btn btn-secondary btn-lg rounded-pill px-4" onclick="history.back()">
                    <i class="fas fa-arrow-left me-2"></i> Previous Class
                </button>
                <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                    Next Class <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="fw-bold text-info fs-4 mb-2">Inspire Tech Academy</div>
                <p>Advanced Python is the gateway to AI. We train students in Nowshera to think like world-class software engineers.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>Location: Khattak Building, Nowshera Cantt</p>
                <p>Contact: 03462345453</p>
            </div>
        </div>
        <hr class="bg-secondary">
        <center class="small opacity-50">© 2026 Inspire Tech. All Rights Reserved.</center>
    </div>
</footer>

<script src="/support-hub.js">
    let step = 1;
    function processQuiz(isCorrect) {
        const feedback = document.getElementById('feedback');
        const questionText = document.getElementById('question-text');
        const optionsContainer = document.getElementById('options-container');

        if (isCorrect) {
            feedback.innerHTML = "<span class='text-success animate__animated animate__fadeInUp'>✔️ Correct! You are mastering the tools.</span>";
            setTimeout(() => {
                if(step === 1) {
                    step = 2;
                    questionText.innerText = "Q2: In OOP, what do we call the 'blueprint' that defines the properties of an object?";
                    optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(true)">Class</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Method</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Module</div>
                    `;
                    feedback.innerText = "";
                } else {
                    feedback.innerHTML = "<span class='text-info'>🎉 Great work! You've finished the Advanced Python module.</span>";
                    optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Certification: OOP Foundations Cleared!</div>`;
                }
            }, 1800);
        } else {
            feedback.innerHTML = "<span class='text-danger animate__animated animate__shakeX'>❌ Wrong! Think about the terminal command, not the python code. Try again.</span>";
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





