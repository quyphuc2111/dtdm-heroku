<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php</title>
    <style>
        * {
    box-sizing: border-box;
    font-family: sans-serif;
    font-weight: bold;
}

body {
    padding: 0;
    margin: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #002b80;
}
.container {
    width: 800px;
    max-width: 80%;
    background-color: white;
    border-radius: 5px;
    padding: 10px;
    box-shadow: 0 0 15px 1px;
}
.score {
    text-align: right;
    color: red;
}
.btn-grid {
    display: grid;
    grid-template-columns: auto auto;
    grid-gap: 10px;
    margin: 30px 0;
}
.btn {
    border-color:  4d88ff;
    background-color: #4d88ff;
    border-radius: 5px;
    padding: 10px 0 ;
    color: #fff;
    outline: none;
    cursor: pointer;
}
.btn:hover {
    border-color:  #333333;
}

.btn.correct {
    background-color: #33cc33;
    color: #333333;
}
.btn.incorrect {
    background-color: red;
}
.start-btn, .next-btn,.restart-btn {
    font-size: 1.5rem;
    padding: 15px 30px;
}
.controls {
    display: flex;
    justify-content: center;
    align-items: center;
}
.hide {
    display: none;
}

    </style>
</head>
<body>
<div class="container">
        <div id="question-container" class="hide">
            <div id="question">Question</div>
            <div id="score" class="score">Score: 8</div>
            <div id="answer-buttons" class="btn-grid"></div>
        </div>
        <div class="controls">
            <button id="start-btn" class="start-btn btn">Start</button>
            <button id="next-btn" class="next-btn btn hide">Next</button>
            <button id="restart-btn" class="restart-btn btn hide">Restart</button>
        </div>
    </div>

    <script>
        // const data = {"response_code":0,"results":[{"category":"Sports","type":"multiple","difficulty":"easy","question":"In what sport is a &quot;shuttlecock&quot; used?","correct_answer":"Badminton","incorrect_answers":["Table Tennis","Rugby","Cricket"]},{"category":"Sports","type":"multiple","difficulty":"easy","question":"This Canadian television sportscaster is known for his &quot;Hockey Night in Canada&quot; role, a commentary show during hockey games.","correct_answer":"Don Cherry","incorrect_answers":["Don McKellar","Don Taylor ","Donald Sutherland"]},{"category":"Sports","type":"multiple","difficulty":"easy","question":"When was the first official international game played?","correct_answer":"1872","incorrect_answers":["1880","1863","1865"]},{"category":"Sports","type":"multiple","difficulty":"easy","question":"Which boxer was banned for taking a bite out of Evander Holyfield&#039;s ear in 1997?","correct_answer":"Mike Tyson","incorrect_answers":["Roy Jones Jr.","Evander Holyfield","Lennox Lewis"]},{"category":"Sports","type":"multiple","difficulty":"easy","question":"Who won the UEFA Champions League in 2017?","correct_answer":"Real Madrid C.F.","incorrect_answers":["Atletico Madrid","AS Monaco FC","Juventus F.C."]}]}
// console.log(data)

const url = 'https://opentdb.com/api.php?amount=5&category=21&difficulty=easy&type=multiple'
fetch(url).then((response) => (response.json()))
.then((data) => {
    console.log(data)

    const quiz =[];
    let score = 0;
    for(let e in data.results) {
        
        quiz.push({
            question: data.results[e].question,
            answers: data.results[e].incorrect_answers.concat(data.results[e].correct_answer),
            correctAnswer: data.results[e].correct_answer
        })
    }
    
    
    console.log(quiz)
    
    let currentQuestion = 0;
    const startBtn = document.getElementById('start-btn');
    const nextBtn = document.getElementById('next-btn');
    const restartBtn = document.getElementById('restart-btn')
    const questionContainer = document.getElementById('question-container');
    const questionElement = document.getElementById('question');
    const answerButtons = document.getElementById('answer-buttons');
    const scoree = document.getElementById('score')
    startBtn.addEventListener('click', startGame)
    nextBtn.addEventListener('click', ()=> {
        currentQuestion++;
        console.log(currentQuestion)
        setNextQuestion();
       
     
    })
    
    
    function startGame() {
        
        startBtn.classList.add('hide');
        questionContainer.classList.remove('hide');
        setNextQuestion(); 
    }
    
    function setNextQuestion() {
        resetState();

        if(currentQuestion == quiz.length) {
            questionElement.innerHTML = "";
            scoree.style.textAlign ='center'
           restartBtn.classList.remove('hide')
           restartBtn.addEventListener('click', () => {
            location.reload();
           })
        } else {
            showQuestion(quiz[currentQuestion])
            showScore()
        }
        
       
        
           
    }
    
    function showQuestion(question) {
       
        questionElement.innerHTML = question.question;
        question.answers.forEach(answer => {
            const button = document.createElement('button')
            button.innerText = answer;
            button.classList.add('btn')
            if(button.textContent === quiz[currentQuestion].correctAnswer) {
                button.dataset.correct = true;
            } else {
                button.dataset.correct = false;
            }
            button.addEventListener('click', selectAnswer) 
            answerButtons.appendChild(button)
        })
    }
    
    function resetState() {
        nextBtn.classList.add('hide')
        while(answerButtons.firstChild) {
            answerButtons.removeChild(answerButtons.firstChild)
        }
    }
    function selectAnswer(e) {
        const selectedButton = e.target;
        if(selectedButton.dataset.correct == 'true') {
            score+=5;
        }
        console.log(selectedButton)
       Array.from(answerButtons.children).forEach(button => { 
        
            setStatusClass(button,button.dataset.correct)
           
       })
       nextBtn.classList.remove('hide')
    }
    
    function setStatusClass(element, correct) {
        clearStatusClass(element);
        if(correct == 'true') {
           element.classList.add('correct')
        } else {
            element.classList.add('incorrect')
        }
    }
    function clearStatusClass(element) {
        element.classList.remove('correct')
        element.classList.remove('incorrect')
    }
    
   function showScore() {
       
       scoree.innerHTML = `Score: ${score}`
   }


})


    </script>
</body>
</html>