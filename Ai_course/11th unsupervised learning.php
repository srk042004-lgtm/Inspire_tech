<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 11 - Clustering & Discovery | Inspire Tech Academy</title>

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
  <body class="ai-course-page ai-course-page">
    <?php include 'navbar.php'; ?>

    <div class="container">
      <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
          <div class="sidebar">
            <h5>Ai_course Map</h5>
            <a
              href="10th supervised learning.php"
              class="class-item"
              ><i class="fas fa-lock"></i> 10. Supervised Learning of AI</a
            >
            <a
              href="11th unsupervised learning.php"
              class="class-item active"
              ><i class="fas fa-lock"></i> 11. Unsupervised Learning of AI</a
            >
            <a
              href="12th reinforcement learning.php"
              class="class-item"
              ><i class="fas fa-lock"></i> 12. Reinforcement Learning of AI</a
            >
          </div>
        </div>

        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="discovery-pill">
              <i class="fas fa-search me-2"></i> Unlabeled Discovery
            </div>
            <h2 class="topic-header">Class 32: Introduction to Clustering</h2>
            <p>
              Clustering is the most powerful tool in
              <strong>Unsupervised Learning</strong>, used to group data points
              that share similar characteristics without any prior knowledge of
              what those groups are. Unlike Classification, where we tell the
              machine "this is a cat," in Clustering, we simply provide the data
              and say, "find the natural groups here." This is essential for
              business intelligence, such as segmenting customers into different
              "personas" based on their spending habits. In this class, we
              introduce the <strong>K-Means Clustering</strong> algorithm, which
              works by placing "centroids" in the data and moving them until
              each group is as tight and distinct as possible. We also cover the
              <strong>Elbow Method</strong>, a mathematical way to determine the
              perfect number of clusters ($K$) for any given dataset. At Inspire
              Tech, we teach you that clustering is the first step in exploring
              large, messy datasets where you don't yet know what answers you
              are looking for.
            </p>

            <div class="algorithm-note">
              <strong>Core Logic:</strong> Clustering minimizes the distance
              between points in the same group while maximizing the distance
              between the groups themselves.
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/EItlUEPCIzM"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="discovery-pill">
              <i class="fas fa-flask me-2"></i> Practical Lab
            </div>
            <h2 class="topic-header">
              Class 33: Project - Customer Segmentation for Marketing
            </h2>
            <p>
              In this project, we apply Clustering to solve a real-world
              business problem: <strong>Targeted Marketing</strong>. We take a
              dataset of mall customers that includes their ages, annual
              incomes, and "Spending Scores." Your task is to build a model that
              automatically groups these customers into categories such as "High
              Spenders," "Budget-Conscious," or "Target-Aged Shoppers." You will
              learn how to use <strong>Scikit-Learn's KMeans</strong> library to
              perform the grouping and then use <strong>Seaborn</strong> to
              create beautiful 2D and 3D scatter plots of the clusters. This
              project is a favorite among employers because it shows you can
              provide "Actionable Insights"—turning a list of 500 random
              customers into 5 strategic marketing groups. By the end of this
              class, you will be able to tell a business exactly who their most
              valuable customers are and how they differ from the rest, all
              without the machine ever being told what a "valuable customer"
              looks like.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/4b5d3muPQmA"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-lightbulb me-2"></i> Logic Check
            </h3>
            <div id="quiz-content">
              <h5 id="question-text">
                Q1: Which algorithm is most commonly used to group similar data
                points together based on their distance?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="processQuiz(true)">
                  K-Means Clustering
                </div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  Linear Regression
                </div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  Logic Gates
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
              <i class="fas fa-arrow-left me-2"></i> Supervised Projects
            </button>
            <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
              Next: Association Rules <i class="fas fa-arrow-right ms-2"></i>
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
              Finding the hidden logic in data. We are Nowshera's premier
              institute for Data Science and Artificial Intelligence.
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
            "<span class='text-success animate__animated animate__fadeIn'>Correct! K-Means uses distance to find clusters.</span>";
          setTimeout(() => {
            if (currentStep === 1) {
              currentStep = 2;
              questionText.innerText =
                "Q2: What is the name of the method used to find the 'Optimal Number' of clusters for K-Means?";
              optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(false)">The Knee Method</div>
                        <div class="quiz-option" onclick="processQuiz(true)">The Elbow Method</div>
                        <div class="quiz-option" onclick="processQuiz(false)">The Wrist Method</div>
                    `;
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info'>🎉 Discovery Expert! You've mastered Unsupervised Foundations.</span>";
              optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Module Complete: Clustering Techniques.</div>`;
            }
          }, 1800);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>Wrong! Hint: We are looking for an Unsupervised Algorithm. Try again.</span>";
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>




