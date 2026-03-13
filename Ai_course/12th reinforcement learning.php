<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 12 - Reinforcement Learning | Inspire Tech Academy</title>

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
    />
    <link rel="stylesheet" href="../style.css" />
  </head>
  <body class="ai-course-page">
    <?php include 'navbar.php'; ?>

    <div class="container">
    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar">
                <h5>Ai_course Map</h5>
                <a href="11th unsupervised learning.php" class="class-item"><i class="fas fa-lock"></i> 11. Unsupervised Learning of AI</a>
                <a href="12th reinforcement learning.php" class="class-item active"><i class="fas fa-lock"></i> 12. Reinforcement Learning of AI</a>
                <a href="13th libraries.php" class="class-item"><i class="fas fa-lock"></i> 13. AI Libraries</a>
                
            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="rl-badge">
              <i class="fas fa-robot me-2"></i> Autonomous Behavior
            </div>
            <h2 class="topic-header">
              Class 34: Reinforcement Learning (RL) Concepts
            </h2>
            <p>
              Reinforcement Learning is a unique area of Machine Learning
              inspired by behavioral psychology; it focuses on how an
              <strong>Agent</strong> should take <strong>Actions</strong> in an
              <strong>Environment</strong> to maximize a cumulative
              <strong>Reward</strong>. Unlike Supervised Learning, where we give
              the machine the correct answer, in RL, the agent must discover
              which actions result in the most reward through trial and error.
              Every time the agent makes a move, the environment provides a
              "Feedback Loop" consisting of the next <strong>State</strong> and
              a reward (or penalty). The ultimate goal is to learn a
              <strong>Policy</strong>—a strategy that tells the agent what to do
              in any given situation to win the long-term game. We explore key
              concepts like the <i>Exploration vs. Exploitation</i> trade-off,
              where the agent must decide whether to try something new or stick
              to what has worked before. At Inspire Tech, we teach you that RL
              is the backbone of robotics and real-time decision-making systems
              where data is constantly changing and labels do not exist.
            </p>

            <div class="mechanics-box">
              <strong>The 4 Elements of RL:</strong><br />
              • <strong>Agent:</strong> The AI learner (e.g., a drone pilot).<br />
              • <strong>Environment:</strong> The world the agent lives in.<br />
              • <strong>Action:</strong> What the agent does (move left, right,
              fly up).<br />
              • <strong>Reward:</strong> The score (+1 for landing, -10 for
              crashing).
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/JgvyzIkgxF0"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>

            <p>
              In this module, we also touch upon <strong>Q-Learning</strong>, a
              popular algorithm that uses a "Q-Table" to store the expected
              rewards for every possible action in every state. As the agent
              gains experience, it updates this table, gradually becoming
              smarter until it can solve complex puzzles or navigate mazes
              perfectly. This is how AI learned to beat human world champions at
              games like Chess and Go—by playing millions of games against
              itself and learning from every single win and loss. Understanding
              RL is the final step in moving from basic data prediction to
              creating truly "intelligent" agents that can operate independently
              in the real world.
            </p>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-puzzle-piece me-2"></i> RL Logic Challenge
            </h3>
            <div id="quiz-content">
              <h5 id="question-text">
                Q1: In Reinforcement Learning, what do we call the 'Score' the
                agent receives after performing an action?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="processQuiz(false)">
                  A Label
                </div>
                <div class="quiz-option" onclick="processQuiz(true)">
                  A Reward
                </div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  A Cluster
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
              <i class="fas fa-arrow-left me-2"></i> Clustering Project
            </button>
            <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
              Next: Neural Networks <i class="fas fa-arrow-right ms-2"></i>
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
              Building autonomous intelligence in the heart of Nowshera. We
              train students to master the most advanced branches of AI.
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
      let currentStep = 1;
      function processQuiz(isCorrect) {
        const feedback = document.getElementById("feedback");
        const questionText = document.getElementById("question-text");
        const optionsContainer = document.getElementById("options-container");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__bounceIn'>✔️ Correct! Rewards guide the agent toward the goal.</span>";
          setTimeout(() => {
            if (currentStep === 1) {
              currentStep = 2;
              questionText.innerText =
                "Q2: What is the name of the strategy that tells an agent what action to take in each state?";
              optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(true)">Policy</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Regression</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Gradient</div>
                    `;
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info'>🎉 RL Concept Mastered! Ready for Deep Learning.</span>";
              optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Module Complete: Autonomous Agents.</div>`;
            }
          }, 1800);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>Wrong! Think about how we motivate a player in a game. Try again.</span>";
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>




