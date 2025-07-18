<!DOCTYPE html>
<html>
<head>
    <title>Absensi - {{ $attendance->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #34495e;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        #message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        .success-container {
            text-align: center;
            padding: 40px 20px;
        }
        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-message {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .back-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>出席が完了しました</h2>
            <div class="success-message">
                {{ session('success') }}
            </div>
            <div>
                <p>ID: {{ session('user_id') }}</p>
                <p>氏名: {{ session('name') }}</p>
                <p>時刻: {{ session('scan_time') }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-primary back-button">
                戻る
            </a>
        </div>
        @else
        <h1>{{ $attendance->title }}</h1>
        <p>出席のためにあなたの情報を入力してください</p>
        
        <form id="attendanceForm">
            <div class="form-group">
                <label for="user_id">ID/NIM/NIP</label>
                <input type="text" id="user_id" name="user_id" required placeholder="あなたのID/NIM/NIPを入力してください">
            </div>
            <div class="form-group">
                <label for="name">氏名</label>
                <input type="text" id="name" name="name" required placeholder="あなたの氏名を入力してください">
            </div>
            <button type="submit" id="submitBtn">出席を送信</button>
        </form>
        
        <div id="message"></div>
        @endif
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        document.getElementById('attendanceForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = '処理中...';
            
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'none';
            
            const formData = {
                user_id: document.getElementById('user_id').value.trim(),
                name: document.getElementById('name').value.trim(),
                _token: '{{ csrf_token() }}'
            };
            
            try {
                const response = await fetch('{{ route("attendance.process", $attendance->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'サーバーでエラーが発生しました');
                }
                
                // Redirect to success page with data
                const successUrl = new URL(window.location.href);
                successUrl.searchParams.append('success', 'true');
                
                // Submit a hidden form to redirect with session data
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('attendance.success', $attendance->id) }}";
                
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';
                form.appendChild(tokenInput);
                
                const dataInput = document.createElement('input');
                dataInput.type = 'hidden';
                dataInput.name = 'attendance_data';
                dataInput.value = JSON.stringify(data);
                form.appendChild(dataInput);
                
                document.body.appendChild(form);
                form.submit();
                
            } catch (error) {
                console.error('Error:', error);
                messageDiv.className = 'error';
                messageDiv.textContent = error.message || 'データ送信中にエラーが発生しました';
                messageDiv.style.display = 'block';
                
                messageDiv.scrollIntoView({ behavior: 'smooth' });
                submitBtn.disabled = false;
                submitBtn.textContent = '出席を送信';
            }
        });

        // Input validation
        document.getElementById('user_id')?.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });

        document.getElementById('name')?.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    </script>
</body>
</html>