<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      AI Class 14 - Model Lifecycle & Projects | Inspire Tech Academy
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
                <a href="13th libraries.php" class="class-item"><i class="fas fa-lock"></i> 13. AI Libraries</a>
                <a href="14th project.php" class="class-item active"><i class="fas fa-lock"></i> 14. Projects of AI</a>
                <a href="15th Deep learning.php" class="class-item"><i class="fas fa-lock"></i> 15. Deep Learning of AI</a>
                


            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="status-pill lab-pill">
              <i class="fas fa-envelope-open-text me-2"></i> NLP Lab
            </div>
            <h2 class="topic-header">
              Class 37: Project - AI Spam Detection System
            </h2>
            <p>
              In this project, we bridge the gap between text and math by
              building a
              <strong>Natural Language Processing (NLP)</strong> classifier. You
              will learn how to use <code>CountVectorizer</code> or
              <code>TfidfVectorizer</code> to transform human language into
              numerical "Feature Vectors" that an AI can understand. We utilize
              the Naive Bayes algorithm, known for its incredible speed and
              accuracy in text classification. This project covers the essential
              <strong>Tokenization</strong> and
              <strong>Stopword Removal</strong> steps, ensuring the model
              focuses only on meaningful words rather than "is," "the," or
              "and." By the end of this class, you will have a script that can
              take any raw email text and predict with high probability whether
              it is "Ham" (safe) or "Spam." This is a cornerstone project for
              any AI portfolio, demonstrating your ability to handle
              unstructured text data and convert it into a binary classification
              result.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/fA5YatOdfpI"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="status-pill lab-pill">
              <i class="fas fa-chart-line me-2"></i> Regression Lab
            </div>
            <h2 class="topic-header">
              Class 38: Project - Advanced Price Prediction
            </h2>
            <p>
              Moving beyond basic regression, this project tackles
              <strong>Multivariate Analysis</strong> where we predict complex
              outcomes based on multiple interacting factors. We use a dataset
              like Car Price Prediction or Stock Trends, focusing on how to
              handle "Categorical Data" through
              <strong>One-Hot Encoding</strong>. You will implement the Random
              Forest Regressor, a powerful "Ensemble" method that combines
              multiple decision trees to produce a more accurate and stable
              prediction. This class emphasizes
              <strong>Feature Engineering</strong>—the art of creating new data
              columns (like "Car Age" from "Year") to help the model see hidden
              patterns. At Inspire Tech, we treat this project as a simulation
              of a real-world business consulting task, teaching you how to
              present your findings to stakeholders using visualization tools
              like Plotly. This project proves you can handle high-dimensional
              data and non-linear relationships effectively.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/g9c66TUylZ4"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="status-pill training-pill">
              <i class="fas fa-dumbbell me-2"></i> Optimization
            </div>
            <h2 class="topic-header">
              Class 39: Deep Dive into Model Training
            </h2>
            <p>
              Model Training is the iterative process of allowing your algorithm
              to see data and minimize its error through
              <strong>Backpropagation</strong> and
              <strong>Gradient Descent</strong>. In this class, we move away
              from "black-box" coding to understand
              <strong>Hyperparameter Tuning</strong>—adjusting knobs like
              Learning Rate, Batch Size, and Epochs to reach the "Global
              Minimum" of the loss function. You will learn about
              <strong>Validation Sets</strong>, which act as a practice exam for
              the model before the final test. We also introduce "Early
              Stopping" to prevent <strong>Overfitting</strong>, ensuring the
              model doesn't just memorize the training data but truly
              generalizes to new information. Understanding the training phase
              is what separates a beginner from a professional engineer; it
              requires patience, monitoring "Loss Curves," and knowing exactly
              when your model has reached its peak performance. This class
              provides the scientific rigor needed to build reliable AI systems.
            </p>

            <div class="code-window mt-3">
              # Example Training Loop Logic<br />
              model.compile(optimizer='adam', loss='mse')<br />
              history = model.fit(X_train, y_train, epochs=50,
              validation_split=0.2)
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/IHZwWFHWa-w"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="status-pill training-pill">
              <i class="fas fa-vial me-2"></i> Validation
            </div>
            <h2 class="topic-header">
              Class 40: Model Testing & Evaluation Metrics
            </h2>
            <p>
              The final and most critical stage of the AI pipeline is
              <strong>Model Testing</strong>, where we evaluate our model on
              "Hold-out Data" it has never seen before. Accuracy is often a lie,
              so we dive deep into
              <strong>Precision, Recall, and the F1-Score</strong> to see how
              the model handles specific classes. We explore the
              <strong>ROC-AUC Curve</strong>, which visualizes the trade-off
              between sensitivity and specificity—crucial for medical or
              security AI applications. You will learn how to generate a
              <strong>Confusion Matrix</strong> and interpret it to see if your
              model is biased toward a specific result. At Inspire Tech, we
              teach you that a model is only as good as its performance on the
              test set; if it fails here, we must go back to Class 39 and
              re-train. This class gives you the confidence to say exactly how
              reliable your AI is before it goes live into production,
              protecting you from costly mistakes in real-world deployments.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/HBi-P5j0Kec"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-microchip me-2"></i> Engineer's Final Exam
            </h3>
            <div id="quiz-content">
              <h5 id="question-text">
                Q1: What do we call the process of adjusting parameters like
                'Learning Rate' to improve a model?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="processQuiz(true)">
                  Hyperparameter Tuning
                </div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  Vectorization
                </div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  Data Cleaning
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
              <i class="fas fa-arrow-left me-2"></i> DL Frameworks
            </button>
            <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
              Next: Deployment <i class="fas fa-arrow-right ms-2"></i>
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
              From code to reality. We empower the youth of Nowshera to build
              intelligent systems that solve real-world problems.
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
            "<span class='text-success animate__animated animate__fadeIn'>✔️ Correct! Tuning is the secret to high-performance models.</span>";
          setTimeout(() => {
            if (currentStep === 1) {
              currentStep = 2;
              questionText.innerText =
                "Q2: Which evaluation metric is best for seeing where the model gets confused between specific classes?";
              optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(false)">R-Squared</div>
                        <div class="quiz-option" onclick="processQuiz(true)">Confusion Matrix</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Mean Absolute Error</div>
                    `;
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info'>🎉 Certification Level: Senior ML Engineer Ready!</span>";
              optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Final Project Labs Cleared.</div>`;
            }
          }, 1800);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>Wrong! Hint: We are 'Tuning' the settings of the algorithm. Try again.</span>";
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>





