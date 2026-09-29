<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard Report - {{ now()->format('Y-m-d') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 11px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        
        .stats-cell.header {
            background-color: #667eea;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .stats-cell.label {
            background-color: #f8f9fa;
            font-weight: 600;
            width: 40%;
        }
        
        .stats-cell.value {
            text-align: center;
            font-weight: bold;
            color: #667eea;
            width: 20%;
        }
        
        .highlight-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Analytics Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">📊 Content Statistics</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Category</div>
                <div class="stats-cell header">Total Count</div>
                <div class="stats-cell header">Last 7 Days</div>
                <div class="stats-cell header">Last 30 Days</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">News Articles</div>
                <div class="stats-cell value">{{ $stats['news'] }}</div>
                <div class="stats-cell value">{{ $weeklyStats['news'] }}</div>
                <div class="stats-cell value">{{ $monthlyStats['news'] }}</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Events</div>
                <div class="stats-cell value">{{ $stats['events'] }}</div>
                <div class="stats-cell value">{{ $weeklyStats['events'] }}</div>
                <div class="stats-cell value">{{ $monthlyStats['events'] }}</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Programs</div>
                <div class="stats-cell value">{{ $stats['programs'] }}</div>
                <div class="stats-cell value">-</div>
                <div class="stats-cell value">-</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Team Members</div>
                <div class="stats-cell value">{{ $stats['team_members'] }}</div>
                <div class="stats-cell value">-</div>
                <div class="stats-cell value">-</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Gallery Images</div>
                <div class="stats-cell value">{{ $stats['gallery_images'] }}</div>
                <div class="stats-cell value">-</div>
                <div class="stats-cell value">-</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Testimonials</div>
                <div class="stats-cell value">{{ $stats['testimonials'] }}</div>
                <div class="stats-cell value">-</div>
                <div class="stats-cell value">{{ $monthlyStats['testimonials'] }}</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Communities</div>
                <div class="stats-cell value">{{ $stats['communities'] }}</div>
                <div class="stats-cell value">{{ $weeklyStats['communities'] }}</div>
                <div class="stats-cell value">-</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Hero Slides</div>
                <div class="stats-cell value">{{ $stats['hero_slides'] }}</div>
                <div class="stats-cell value">-</div>
                <div class="stats-cell value">-</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">📬 Messages & Pending Items</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Category</div>
                <div class="stats-cell header">Count</div>
                <div class="stats-cell header">Last 7 Days</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Contact Messages</div>
                <div class="stats-cell value">{{ $stats['contact_messages'] }}</div>
                <div class="stats-cell value">{{ $weeklyStats['messages'] }}</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Unread Messages</div>
                <div class="stats-cell value">{{ $stats['unread_messages'] }}</div>
                <div class="stats-cell value">-</div>
            </div>
            
            <div class="stats-row">
                <div class="stats-cell label">Pending Communities</div>
                <div class="stats-cell value">{{ $stats['pending_communities'] }}</div>
                <div class="stats-cell value">-</div>
            </div>
        </div>
    </div>

    @if($stats['unread_messages'] > 0 || $stats['pending_communities'] > 0)
    <div class="highlight-box">
        <strong>⚠️ Action Required:</strong><br>
        @if($stats['unread_messages'] > 0)
        • You have <strong>{{ $stats['unread_messages'] }}</strong> unread message(s) requiring attention.<br>
        @endif
        @if($stats['pending_communities'] > 0)
        • You have <strong>{{ $stats['pending_communities'] }}</strong> community application(s) pending approval.
        @endif
    </div>
    @endif

    <div class="section">
        <div class="section-title">📈 Weekly Activity Summary</div>
        <p style="line-height: 1.6;">
            <strong>Total Weekly Activity:</strong> {{ $weeklyStats['news'] + $weeklyStats['events'] + $weeklyStats['communities'] + $weeklyStats['messages'] }} items<br>
            • News: {{ $weeklyStats['news'] }} <span class="badge badge-success">+{{ $weeklyStats['news'] }}</span><br>
            • Events: {{ $weeklyStats['events'] }} <span class="badge badge-success">+{{ $weeklyStats['events'] }}</span><br>
            • Communities: {{ $weeklyStats['communities'] }} <span class="badge badge-success">+{{ $weeklyStats['communities'] }}</span><br>
            • Messages: {{ $weeklyStats['messages'] }} <span class="badge badge-success">+{{ $weeklyStats['messages'] }}</span>
        </p>
    </div>

    <div class="footer">
        <p><strong>IEYDA Dashboard Report</strong> | Generated automatically from system data</p>
        <p>This report is confidential and intended for authorized personnel only.</p>
    </div>
</body>
</html>
