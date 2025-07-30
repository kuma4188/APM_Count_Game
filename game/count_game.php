<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>10초 맞추기 게임</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
            height: 100vh;
        }
        .sidebar {
            width: 20%;
            padding: 20px;
            background: #f0f0f0;
            overflow-y: auto;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        h1 { margin-bottom: 30px; }
        #stopwatch {
            font-size: 60px;
            margin: 20px 0;
        }
        .buttons button {
            font-size: 18px;
            padding: 10px 20px;
            margin: 5px;
        }
        .auth-box {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 280px;
        }
        .auth-box input {
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>게임 설명</h3>
        <p>10초에 가장 가까운 시간이 가장 좋은 기록입니다.</p>
        <h4>조작법</h4>
        <ul>
            <li>Start: 시작</li>
            <li>Stop: 멈추고 결과 확인</li>
        </ul>
        <h4>랭킹</h4>
        <ol id="rankingList"></ol>
    </div>

    <div class="content">
        <h1>10초 맞추기 게임</h1>
        <div id="stopwatch">00.000</div>
        <div class="buttons">
            <button onclick="start()">Start</button>
            <button onclick="stop()">Stop</button>
        </div>
        <div id="result"></div>

        <div class="auth-box">
            <h3>로그인 / 회원가입</h3>
            <input type="text" id="username" placeholder="아이디">
            <input type="password" id="password" placeholder="비밀번호">
            <button onclick="register()">회원가입</button>
            <button onclick="login()">로그인</button>
            <div id="loginStatus"></div>
        </div>
    </div>

    <script>
        let startTime, timerInterval, isRunning = false, lastElapsed = 0;
        let currentUserId = null;

        function formatTime(ms) {
            return (ms / 1000).toFixed(3);
        }

        function start() {
            if (!currentUserId) {
                alert("로그인 후 게임을 이용할 수 있습니다.");
                return;
            }
            if (isRunning) return;
            isRunning = true;
            startTime = Date.now();
            document.getElementById("result").innerText = '';
            timerInterval = setInterval(() => {
                lastElapsed = Date.now() - startTime;
                document.getElementById("stopwatch").innerText = formatTime(lastElapsed);
            }, 10);
        }

        function stop() {
            if (!isRunning) return;
            isRunning = false;
            clearInterval(timerInterval);

            const timeInSeconds = lastElapsed / 1000;
            const diff = Math.abs(timeInSeconds - 10);
            const resultEl = document.getElementById("result");

            if (diff < 0.005) {
                resultEl.innerHTML = `<span style="color: blue;">정확히 10초! 성공!</span>`;
            } else {
                const color = (timeInSeconds < 10) ? 'red' : 'green';
                const symbol = (timeInSeconds < 10) ? '-' : '+';
                resultEl.innerHTML = `<span style="color:${color};">오차: ${symbol}${diff.toFixed(3)}초</span>`;
            }

            saveRecord(timeInSeconds, diff);
        }

        async function register() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            const res = await fetch('register.php', {
                method: 'POST',
                body: JSON.stringify({ username, password })
            });
            const result = await res.json();
            alert(result.message);
        }

        async function login() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            const res = await fetch('login.php', {
                method: 'POST',
                body: JSON.stringify({ username, password })
            });
            const result = await res.json();
            alert(result.message);
            if (result.success) {
                currentUserId = result.user_id;
                document.getElementById("loginStatus").innerText = username + " 님 로그인됨";
                fetchRanking();
            }
        }

        async function saveRecord(time, diff) {
            await fetch('save_record.php', {
                method: 'POST',
                body: JSON.stringify({ user_id: currentUserId, time, diff })
            });
            fetchRanking();
        }

        async function fetchRanking() {
            const res = await fetch('get_ranking.php');
            const data = await res.json();
            const list = document.getElementById("rankingList");
            list.innerHTML = '';
            data.forEach((r, i) => {
                list.innerHTML += `${i + 1}등 - ${r.username} ( ${r.best_diff.toFixed(3)}초)<br>` ;
            });
        }
    </script>
</body>
</html>

