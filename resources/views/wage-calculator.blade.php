<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uber Minimum Wage Calculator</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'UberMove', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #000000;
            min-height: 100vh;
            color: #ffffff;
            line-height: 1.6;
        }

        .header {
            background: #000000;
            padding: 20px 0;
            border-bottom: 1px solid #333;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
        }

        .uber-logo {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -1px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px 60px 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: #000000;
        }

        h1 {
            color: #000000;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #6B7280;
            font-size: 16px;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #000000;
            font-size: 14px;
        }

        select, input {
            width: 100%;
            padding: 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: #ffffff;
            color: #000000;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
        }

        select:hover, input:hover {
            border-color: #9CA3AF;
        }

        small {
            color: #6B7280;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .calculate-btn {
            width: 100%;
            padding: 16px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .calculate-btn:hover {
            background: #1F2937;
            transform: translateY(-1px);
        }

        .calculate-btn:active {
            transform: translateY(0);
        }

        .gov-link {
            text-align: center;
            margin: 24px 0;
            padding: 16px;
            background: #F3F4F6;
            border-radius: 8px;
            border-left: 4px solid #000000;
        }

        .gov-link p {
            margin: 0;
            color: #374151;
            font-size: 14px;
        }

        .gov-link a {
            color: #000000;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .gov-link a:hover {
            color: #1F2937;
            text-decoration: underline;
        }

        .result {
            margin-top: 32px;
            padding: 24px;
            background: #F9FAFB;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            display: none;
        }

        .result.show {
            display: block;
        }

        .result h3 {
            color: #000000;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 600;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #E5E7EB;
        }

        .result-item:last-child {
            border-bottom: 2px solid #000000;
            font-weight: 700;
            font-size: 18px;
            color: #000000;
            margin-top: 8px;
            padding-top: 16px;
        }

        .result-item span:first-child {
            color: #374151;
            font-weight: 500;
        }

        .result-item span:last-child {
            color: #000000;
            font-weight: 600;
        }

        .error {
            color: #DC2626;
            text-align: center;
            margin-top: 16px;
            font-weight: 500;
        }

        .loading {
            text-align: center;
            color: #6B7280;
            font-weight: 500;
        }

        .accent-green {
            color: #059669;
        }

        .accent-gray {
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="uber-logo">Uber</div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h1>Minimum Wage Calculator</h1>
            <p class="subtitle">
                Calculate your total earnings including minimum wage, net fare, and tips. Ontario uses Digital Platform Workers' Rights Act ($17.20/hour)
            </p>

            <form id="wageForm">
            <div class="form-group">
                <label for="province">Select Province:</label>
                <select id="province" name="province_code" required>
                    <option value="">-- Select Province --</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province['province_code'] }}" {{ $detectedProvince === $province['province_code'] ? 'selected' : '' }}>
                            {{ $province['province_name'] }} 
                            @if($province['digital_platform_wage'])
                                (Digital Platform: ${{ number_format($province['digital_platform_wage'], 2) }}/hr)
                            @else
                                (Min Wage: ${{ number_format($province['minimum_wage'], 2) }}/hr)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="activeTime">Active Time (Hours:Minutes):</label>
                <input type="text" id="activeTime" name="active_time" placeholder="Enter time (e.g., 8:30)" pattern="^\d{1,2}:\d{2}$" required>
                <small style="color: #666; font-size: 12px;">Format: H:MM (e.g., 8:30 for 8 hours 30 minutes)</small>
            </div>

            <div class="form-group">
                <label for="netFare">Net Fare ($):</label>
                <input type="number" id="netFare" name="net_fare" step="0.01" min="0" placeholder="Enter net fare earnings (optional)">
            </div>

            <div class="form-group">
                <label for="tips">Tips ($):</label>
                <input type="number" id="tips" name="tips" step="0.01" min="0" placeholder="Enter tips received (optional)">
            </div>

                <button type="submit" class="calculate-btn">Calculate Total Earnings</button>
            </form>

            <div class="gov-link">
                <p>
                    📋 <strong>Official Government Reference:</strong><br>
                    <a href="https://minwage-salairemin.service.canada.ca/en/general.html" target="_blank">
                        Check Current Minimum Wage Rates - Government of Canada
                    </a>
                </p>
            </div>

            <div id="result" class="result">
                <h3>Calculation Result</h3>
                <div id="resultContent"></div>
            </div>

            <div id="error" class="error" style="display: none;"></div>
            <div id="loading" class="loading" style="display: none;">Calculating...</div>
        </div>
    </div>

    <script>
        document.getElementById('wageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('result');
            const errorDiv = document.getElementById('error');
            const loadingDiv = document.getElementById('loading');
            
            resultDiv.classList.remove('show');
            errorDiv.style.display = 'none';
            loadingDiv.style.display = 'block';
            
            fetch('/calculate', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingDiv.style.display = 'none';
                
                if (data.success) {
                    const result = data.data;
                    document.getElementById('resultContent').innerHTML = `
                        <div class="result-item">
                            <span>Province:</span>
                            <span>${result.province_name}</span>
                        </div>
                        <div class="result-item">
                            <span>Active Time:</span>
                            <span>${result.active_time_formatted}</span>
                        </div>
                        <div class="result-item">
                            <span>Hourly Rate:</span>
                            <span>$${result.hourly_rate}</span>
                        </div>
                        <div class="result-item">
                            <span>Wage Type:</span>
                            <span>${result.wage_type}</span>
                        </div>
                        <div class="result-item">
                            <span>Minimum Wage (${result.active_time_formatted} × $${result.hourly_rate}):</span>
                            <span>$${result.minimum_wage_total}</span>
                        </div>
                        <div class="result-item">
                            <span>Hour Total (Minimum - Net Fare):</span>
                            <span>$${result.hour_total}</span>
                        </div>
                        <div class="result-item">
                            <span>Net Fare:</span>
                            <span>$${result.net_fare}</span>
                        </div>
                        <div class="result-item">
                            <span>Tips:</span>
                            <span>$${result.tips}</span>
                        </div>
                        <div class="result-item">
                            <span>Total Earnings:</span>
                            <span>$${result.total_earnings}</span>
                        </div>
                    `;
                    resultDiv.classList.add('show');
                } else {
                    errorDiv.textContent = data.message || 'An error occurred';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                loadingDiv.style.display = 'none';
                errorDiv.textContent = 'Network error occurred';
                errorDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>