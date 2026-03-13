<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 13 - Deep Learning Frameworks | Inspire Tech Academy</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />    <link rel="stylesheet" href="../style.css" /></head>
  <body class="ai-course-page ai-course-page">
    <?php include 'navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar">
                <h5>Ai_course Map</h5>
                <a href="12th reinforcement learning.php" class="class-item"><i class="fas fa-lock"></i> 12. Reinforcement Learning of AI</a>
                <a href="13th libraries.php" class="class-item active"><i class="fas fa-lock"></i> 13. AI Libraries</a>
                <a href="14th project.php" class="class-item"><i class="fas fa-lock"></i> 14. Projects of AI</a>
                


            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="framework-badge tf-badge">
              <i class="fab fa-google me-2"></i> Powered by Google
            </div>
            <h2 class="topic-header">
              Class 35: Introduction to TensorFlow & Keras
            </h2>
            <p>
              <strong>TensorFlow</strong> is an end-to-end open-source platform
              for machine learning, developed by the Google Brain team. It is
              designed to handle complex mathematical operations on large
              datasets using "Data Flow Graphs," where nodes represent
              operations and edges represent the multidimensional data arrays
              known as <strong>Tensors</strong>. In this class, we focus on
              <strong>Keras</strong>, the high-level API for TensorFlow that
              makes building neural networks as easy as stacking LEGO blocks.
              You will learn how to define an architecture using the
              <code>Sequential</code> model, add dense layers, and choose
              activation functions like 'ReLU' or 'Softmax'. TensorFlow is
              particularly famous for its deployment capabilities, allowing you
              to run AI models on servers, browsers (TF.js), and even mobile
              devices (TF Lite). At Inspire Tech, we teach you that TensorFlow
              is the industry standard for production-scale AI, where
              performance and scalability are the top priorities.
            </p>

            <div class="code-block mt-3">
              import tensorflow as tf<br />
              from tensorflow.keras import layers<br /><br />
              model = tf.keras.Sequential([<br />
              &nbsp;&nbsp;layers.Dense(64, activation='relu'),<br />
              &nbsp;&nbsp;layers.Dense(10, activation='softmax')<br />
              ])
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/tPYj3fFJGjk"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="framework-badge pt-badge">
              <i class="fab fa-facebook-f me-2"></i> Powered by Meta (FAIR)
            </div>
            <h2 class="topic-header">Class 36: Introduction to PyTorch</h2>
            <p>
              <strong>PyTorch</strong> has rapidly become the favorite framework
              of the AI research community due to its "Pythonic" nature and its
              use of <strong>Dynamic Computational Graphs</strong>. Unlike older
              frameworks that required you to define the entire model before
              running it, PyTorch builds the graph on the fly, which allows for
              much easier debugging and flexible model architectures. This makes
              it the perfect choice for cutting-edge projects like Natural
              Language Processing (NLP) and Generative AI. In this module, we
              explore the core <code>torch.Tensor</code> object and the
              <code>Autograd</code> system, which automatically calculates the
              gradients needed for backpropagation. You will learn how to build
              a model by inheriting from the <code>nn.Module</code> class,
              providing you with granular control over every single neuron in
              your network. At our academy, we emphasize PyTorch for students
              who want to read the latest AI research papers and build their own
              custom, innovative algorithms from scratch.
            </p>

            <div class="code-block mt-3">
              import torch<br />
              import torch.nn as nn<br /><br />
              class SimpleNet(nn.Module):<br />
              &nbsp;&nbsp;def __init__(self):<br />
              &nbsp;&nbsp;&nbsp;&nbsp;super().__init__()<br />
              &nbsp;&nbsp;&nbsp;&nbsp;self.layer = nn.Linear(10, 2)
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/V_xro1bcAuA"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-microchip me-2"></i> Framework IQ Test
            </h3>
            <div id="quiz-content">
              <h5 id="question-text">
                Q1: Which library is known for using 'Dynamic Graphs', making it
                feel more like regular Python code?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="processQuiz(false)">
                  TensorFlow 1.0
                </div>
                <div class="quiz-option" onclick="processQuiz(true)">
                  PyTorch
                </div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  Scikit-Learn
                </div>
              </div>
              <div id="feedback" class="mt-3 fw-bold"></div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-5 mb-5">
            <button
              class="btn btn-secondary btn-lg rounded-pill px-4"
              onclick="history.back()"
            >
              <i class="fas fa-arrow-left me-2"></i> RL Concepts
            </button>
            <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
              Next: Building Neurons <i class="fas fa-arrow-right ms-2"></i>
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
            <p>
              Mastering the tools of the giants. We bring Silicon Valley
              technology to the students of Nowshera.
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p>Location: Khattak Building, Nowshera Cantt</p>
            <p>Instructor: Raheel Ahmad | 03462345453</p>
          </div>
        </div>
        <hr class="bg-secondary" />
        <center class="small opacity-50">
          © 2026 Inspire Tech. All Rights Reserved.
        </center>
      </div>
    </footer>

    <script>
      let currentStep = 1;
      function processQuiz(isCorrect) {
        const feedback = document.getElementById("feedback");
        const questionText = document.getElementById("question-text");
        const optionsContainer = document.getElementById("options-container");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__fadeIn'>✔️ Correct! PyTorch is loved for its Pythonic flexibility.</span>";
          setTimeout(() => {
            if (currentStep === 1) {
              currentStep = 2;
              questionText.innerText =
                "Q2: In Deep Learning, what do we call the multidimensional arrays that hold our data?";
              optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(false)">Matrices Only</div>
                        <div class="quiz-option" onclick="processQuiz(true)">Tensors</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Data Clusters</div>
                    `;
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info'>🎉 Expert Level! You are ready to build Neural Networks.</span>";
              optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Module Complete: Deep Learning Frameworks.</div>`;
            }
          }, 1800);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>Wrong! Hint: Think about the library used by researchers. Try again.</span>";
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>





