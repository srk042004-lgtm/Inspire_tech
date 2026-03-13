<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      AI Class 15 - Deep Learning Foundations | Inspire Tech Academy
    </title>

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
                <a href="14th project.php" class="class-item"><i class="fas fa-lock"></i> 14. Projects of AI</a>
                <a href="15th Deep learning.php" class="class-item active"><i class="fas fa-lock"></i> 15. Deep Learning of AI</a>
                <a href="16th deep Leaning 2 .php" class="class-item"><i class="fas fa-lock"></i> 16. Deep Learning of AI 2</a>
                


            </div>
        </div>


        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="neural-pill">
              <i class="fas fa-brain me-2"></i> Beyond ML
            </div>
            <h2 class="topic-header">Class 41: What is Deep Learning?</h2>
            <p>
              Deep Learning is a specialized subset of Machine Learning that
              focuses on "Deep" architectures—models with many layers of
              interconnected neurons. While traditional ML algorithms often
              reach a plateau in performance as more data is added, Deep
              Learning models continue to improve, making them ideal for "Big
              Data." These models perform automatic
              <strong>Feature Extraction</strong>; unlike Class 28 where we
              manually selected house features, a Deep Learning model discovers
              which features matter on its own by looking at raw pixels or audio
              waves. This "depth" allows the computer to understand hierarchical
              concepts: the first layer might detect edges, the second detects
              shapes, and the final layer recognizes a human face. At Inspire
              Tech, we teach you that Deep Learning is the "secret sauce" behind
              ChatGPT, Tesla Autopilot, and AlphaGo. Understanding this field
              requires a shift from simple statistics to complex multi-layered
              mathematical transformations that allow machines to perceive the
              world almost like we do.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/6M5VXA7wMlU"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="neural-pill">
              <i class="fas fa-dna me-2"></i> Biological Logic
            </div>
            <h2 class="topic-header">
              Class 42: Understanding Neural Networks
            </h2>
            <p>
              Neural Networks are computational models inspired by the structure
              of the human brain's biological neurons. In the brain, a neuron
              receives signals through dendrites, processes them in the cell
              body, and sends a signal through an axon. Similarly, an artificial
              neuron takes multiple inputs ($x$), multiplies them by
              <strong>Weights</strong> ($w$), adds a
              <strong>Bias</strong> ($b$), and passes the result through an
              <strong>Activation Function</strong>. This function acts as a
              gatekeeper, deciding if the neuron should "fire" or stay silent.
              In this class, we move from the theory of a single neuron (the
              Perceptron) to a network of neurons working together to solve
              problems. You will learn how these connections allow the network
              to model extremely complex, non-linear relationships that
              traditional algorithms cannot handle. At our academy, we visualize
              these networks as a series of mathematical equations that slowly
              "tune" themselves until the machine begins to show signs of
              intelligent behavior.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/aircAruvnKk"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="neural-pill">
              <i class="fas fa-layer-group me-2"></i> Multi-Layer Logic
            </div>
            <h2 class="topic-header">
              Class 43: Artificial Neural Network (ANN) Architecture
            </h2>
            <p>
              An Artificial Neural Network (ANN) is composed of three primary
              layers: the <strong>Input Layer</strong>, one or more
              <strong>Hidden Layers</strong>, and the
              <strong>Output Layer</strong>. The hidden layers are where the
              "Magic" happens; they transform the data through thousands of
              mathematical operations to find the deepest patterns. In this
              class, we explore <strong>Forward Propagation</strong>—the process
              where data travels from input to output to make a guess—and
              <strong>Backpropagation</strong>—the process where the model
              learns from its mistakes by moving backward and adjusting its
              weights. We discuss the importance of activation functions like
              <strong>ReLU</strong> (for hidden layers) and
              <strong>Sigmoid/Softmax</strong> (for output layers). ANNs are
              particularly effective for tabular data and complex classification
              tasks. At Inspire Tech, we guide you through the math of how a
              network optimizes itself using Gradient Descent. Mastering ANN
              architecture is your final step before moving into specialized
              fields like Computer Vision and Natural Language Processing.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/nU6pQ61hT_Q"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-graduation-cap me-2"></i> Deep Learning
              Gatekeeper
            </h3>
            <div class="quiz-progress"><div id="progress-bar"></div></div>

            <div id="quiz-content">
              <h5 id="question-text">
                Q1: What happens to Deep Learning performance as you add
                significantly more data?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="checkAnswer(true)">
                  It continues to improve (no plateau)
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  It stays the same
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  It gets worse
                </div>
              </div>
            </div>
            <div id="feedback" class="mt-3 fw-bold"></div>
          </div>

          <div
            id="nav-container"
            class="d-flex justify-content-between mt-5 mb-5 d-none"
          >
            <button
              class="btn btn-secondary btn-lg rounded-pill px-4"
              onclick="history.back()"
            >
              <i class="fas fa-arrow-left me-2"></i> Previous
            </button>
            <button class="btn btn-success btn-lg rounded-pill px-5 shadow">
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
            <p>
              Building the digital brains of tomorrow. Our curriculum is
              designed to take you from a basic coder to a Deep Learning expert.
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

    <script src="/support hub.js">
      let currentQuestion = 1;
      const questions = [
        {
          q: "What happens to Deep Learning performance as you add significantly more data?",
          options: [
            "It continues to improve (no plateau)",
            "It stays the same",
            "It gets worse",
          ],
          correct: 0,
        },
        {
          q: "In a Neural Network, which component decides if a neuron should 'fire' based on its input?",
          options: ["The Bias", "The Weight", "The Activation Function"],
          correct: 2,
        },
        {
          q: "What are the layers between the Input and Output layers called?",
          options: ["Invisible Layers", "Hidden Layers", "Deep Layers"],
          correct: 1,
        },
      ];

      function checkAnswer(isCorrect) {
        const feedback = document.getElementById("feedback");
        const bar = document.getElementById("progress-bar");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__flash'>Correct! Moving to next question...</span>";
          let progress = (currentQuestion / 3) * 100;
          bar.style.width = progress + "%";

          setTimeout(() => {
            currentQuestion++;
            if (currentQuestion <= 3) {
              loadQuestion();
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info animate__animated animate__tada'>🌟 All correct! Redirecting to Next Class...</span>";
              document
                .getElementById("nav-container")
                .classList.remove("d-none");
              // AUTO-ADVANCE LOGIC
              setTimeout(() => {
                alert(
                  "Access Granted: Advancing to Computer Vision (CNN) Module...",
                );
                // In a real site: window.location.href = "class44.php";
              }, 2000);
            }
          }, 1200);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>Incorrect! Quiz Resetting. Read the details above again.</span>";
          setTimeout(() => {
            currentQuestion = 1;
            bar.style.width = "0%";
            loadQuestion();
            feedback.innerText = "";
          }, 2000);
        }
      }

      function loadQuestion() {
        const qData = questions[currentQuestion - 1];
        document.getElementById("question-text").innerText =
          `Q${currentQuestion}: ${qData.q}`;
        const optionsContainer = document.getElementById("options-container");
        optionsContainer.innerHTML = "";

        qData.options.forEach((opt, index) => {
          const div = document.createElement("div");
          div.className = "quiz-option";
          div.innerText = opt;
          div.onclick = () => checkAnswer(index === qData.correct);
          optionsContainer.appendChild(div);
        });
      }

      // Initialize
      loadQuestion();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>





