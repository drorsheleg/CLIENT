<?php include_once __DIR__ . '/../shared/unified_header.php'; ?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>אזור אישי - קאר וואשר</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ------------------------- */
        /* --- Global Styles --- */
        /* ------------------------- */
        :root {
            --primary-color: #05bbff;
            --primary-hover-color: #04a5e1;
            --secondary-bg-color: #f4f8fb;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --text-dark-color: #212529;
            --text-medium-color: #495057;
            --border-color: #dee2e6;
            --background-light-color: #ffffff;
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Assistant', sans-serif;
            background-color: var(--secondary-bg-color);
            color: var(--text-dark-color);
            margin: 0;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
        }

        .hidden {
            display: none !important;
        }

        /* ------------------------- */
        /* --- Login View --- */
        /* ------------------------- */
        #login-view {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .login-container {
            background-color: var(--background-light-color);
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 { font-size: 24px; margin-bottom: 8px; }
        .login-container p { color: var(--text-medium-color); margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; text-align: right; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; }
        .form-group input { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; font-family: 'Assistant', sans-serif; font-size: 16px; }
        #login-error { color: var(--danger-color); margin-top: 16px; min-height: 20px; font-weight: 600; }
        .spinner { width: 20px; height: 20px; border: 3px solid rgba(255, 255, 255, 0.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ------------------------- */
        /* --- Personal Area View --- */
        /* ------------------------- */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
            box-sizing: border-box;
        }
        .page-hero {
            background-color: var(--primary-color);
            color: var(--background-light-color);
            padding: 24px 32px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-radius: 8px;
            position: static;
        }
        .page-hero h1 { font-size: 28px; font-weight: 700; margin: 0; }
        .page-hero p { font-size: 15px; opacity: 0.85; margin: 4px 0 0 0; }
        .page-hero-icon { font-size: 32px; color: var(--background-light-color); }
        
        .main-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px; }
        .card { background-color: var(--background-light-color); box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04); border-radius: 8px; padding: 24px; display: flex; flex-direction: column; }
        .card.full-width { grid-column: 1 / -1; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; }
        .card-header h3 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; }
        
        .button { border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; font-family: 'Assistant', sans-serif; cursor: pointer; transition: background-color 0.2s ease; font-size: 14px; display: inline-flex; justify-content: center; align-items: center; gap: 8px; }
        .button-primary { background-color: var(--primary-color); color: var(--background-light-color); }
        .button-primary:hover { background-color: var(--primary-hover-color); }
        .button-primary:disabled { background-color: #a0a0a0; cursor: not-allowed; }
        .button-secondary { background-color: var(--secondary-bg-color); color: var(--text-dark-color); border: 1px solid var(--border-color); }
        .button-secondary:hover { background-color: #e8f3f8; }

        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600; }
        .status-success { background-color: #e6f8ef; color: var(--success-color); }
        .status-warning { background-color: #fff8e1; color: var(--warning-color); }
        .status-danger { background-color: #fbeaea; color: var(--danger-color); }
        .status-info { background-color: #e1f5fe; color: var(--primary-color); }
        
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: right; }
        th, td { padding: 12px 16px; border-bottom: 1px solid var(--border-color); }
        thead th { background-color: var(--secondary-bg-color); font-weight: 600; color: var(--text-medium-color); }
        tbody tr:hover { background-color: #f8fcfe; }
        
        .details-list { list-style: none; padding: 0; margin: 0; }
        .details-list li { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .details-list li:last-child { border-bottom: none; }
        .details-list li strong { color: var(--text-medium-color); display: flex; align-items: center; gap: 8px; }
        .details-list a { color: var(--primary-color); font-weight: 600; text-decoration: none; }
        .details-list a:hover { text-decoration: underline; }

        .subscription-progress { margin-top: 16px; }
        .progress-bar-container { background-color: var(--secondary-bg-color); border-radius: 10px; height: 10px; overflow: hidden; }
        .progress-bar { background-color: var(--primary-color); height: 100%; border-radius: 10px; transition: width 0.5s ease-in-out; }
        .progress-text { display: flex; justify-content: space-between; font-size: 13px; margin-top: 8px; }
        .progress-text strong { font-weight: 700; color: var(--primary-color); }
        
        .loading { text-align: center; padding: 40px; font-size: 18px; color: var(--text-medium-color); }
        #subscriptionCard { display: none; }

        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.6); display: flex; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: var(--background-light-color); padding: 24px; border-radius: 8px; width: 90%; max-width: 500px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; }
        .close-button { background: none; border: none; font-size: 24px; cursor: pointer; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
        #edit-details-error { color: var(--danger-color); margin-top: 10px; text-align: right; }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }
            .login-container {
                padding: 24px;
            }
            .page-hero {
                padding: 16px;
                flex-direction: column;
                text-align: center;
            }
            .page-hero h1 {
                font-size: 24px;
            }
            /* --- FINAL MOBILE FIX --- */
            .main-grid {
                display: block; /* Change from grid to simple block layout */
            }
            .card {
                margin-bottom: 16px; /* Add vertical space between cards */
            }
            .card:last-child {
                margin-bottom: 0;
            }
            /* --- END FIX --- */
            .card-header {
                flex-direction: column;
                gap: 12px;
            }
        }

    </style>
</head>
<body>

    <main>
        <div id="login-view">
            <div class="login-container">
                <i class="fas fa-car-side" style="font-size: 40px; color: var(--primary-color); margin-bottom: 16px;"></i>
                <h2>אזור אישי ללקוחות</h2>
                <p>הזן את מספר הטלפון והקוד האישי לצפייה בנתונים</p>
                <form id="login-form">
                    <div class="form-group">
                        <label for="phone">מספר טלפון</label>
                        <input type="tel" id="phone" name="phone" autocomplete="tel" required>
                    </div>
                    <div class="form-group">
                        <label for="pin">קוד אישי (PIN)</label>
                        <input type="password" id="pin" name="pin" maxlength="4" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="button button-primary" style="width: 100%; padding: 12px;">
                        <span id="login-btn-text">התחברות</span>
                        <div id="login-spinner" class="spinner hidden"></div>
                    </button>
                    <div id="login-error"></div>
                </form>
            </div>
        </div>

        <div id="personal-area-view" class="hidden">
            <div class="container">
                <header class="page-hero">
                    <i class="page-hero-icon fas fa-user-circle"></i>
                    <div>
                        <h1 id="hero-title">טוען נתונים...</h1>
                        <p>ברוך הבא לאזור האישי שלך. כאן תוכל לעקוב אחר ההזמנות, המנויים והתשלומים שלך.</p>
                    </div>
                </header>

                <div class="main-grid">
                    <section class="card" id="personalDetailsCard">
                        <div class="card-header">
                            <h3><i class="fas fa-id-card" style="margin-inline-end: 8px;"></i>הפרטים שלי</h3>
                            <button id="edit-details-btn" class="button button-secondary"><i class="fas fa-pencil-alt"></i> עריכת פרטים</button>
                        </div>
                        <div class="card-body"><ul class="details-list" id="details-list-content"><li class="loading">טוען פרטים...</li></ul></div>
                    </section>

                    <section class="card" id="subscriptionCard">
                        <div class="card-header">
                            <h3><i class="fas fa-star" style="margin-inline-end: 8px;"></i>מצב הכרטיסייה</h3>
                            <span id="subscription-status-badge" class="status-badge"></span>
                        </div>
                        <div class="card-body">
                            <h4 id="subscription-type"></h4>
                            <p id="subscription-start-date" style="color: var(--text-medium-color); font-size: 14px;"></p>
                            <div class="subscription-progress">
                                <div class="progress-bar-container"><div id="subscription-progress-bar" class="progress-bar"></div></div>
                                <div class="progress-text">
                                    <span id="subscription-used"></span>
                                    <strong id="subscription-remaining"></strong>
                                    <span id="subscription-total"></span>
                                </div>
                            </div>
                            <div id="subscription-deal-info" style="margin-top: 20px;"></div>
                        </div>
                    </section>

                    <section class="card" id="renewalInfoCard">
                        <div class="card-header"><h3><i class="fas fa-credit-card" style="margin-inline-end: 8px;"></i>לחידוש כרטיסייה</h3></div>
                        <div class="card-body">
                            <ul class="details-list">
                                <li><strong><i class="fas fa-mobile-alt" style="width: 16px;"></i>פייבוקס:</strong><span>054-9952960</span></li>
                                <li><strong><i class="fas fa-link" style="width: 16px;"></i>תשלום אונליין:</strong><a href="https://pay.grow.link/5f156cb1c1b20471ff408e354986ca84-MjM5NTMyNw" target="_blank" rel="noopener noreferrer">לחץ כאן לתשלום</a></li>
                            </ul>
                            <p style="font-size: 13px; color: var(--text-medium-color); margin-top: 16px; text-align: center; background-color: var(--secondary-bg-color); padding: 10px; border-radius: 6px;"><i class="fas fa-info-circle" style="margin-inline-end: 4px;"></i>זהו לינק לתשלום פתוח. יש לכתוב את הסכום שסוכם ולמלא פרטים.</p>
                        </div>
                    </section>

                    <section class="card full-width" id="orderHistoryCard">
                        <div class="card-header"><h3><i class="fas fa-history" style="margin-inline-end: 8px;"></i>היסטוריית הזמנות</h3></div>
                        <div class="table-container">
                            <table>
                                <thead><tr><th>תאריך</th><th>שעה</th><th>מספר רכבים</th><th>מחיר</th><th>סטטוס</th></tr></thead>
                                <tbody id="orders-table-body"><tr><td colspan="5" class="loading">טוען היסטוריית הזמנות...</td></tr></tbody>
                            </table>
                        </div>
                    </section>
                    
                    <section class="card full-width" id="paymentHistoryCard">
                        <div class="card-header"><h3><i class="fas fa-file-invoice-dollar" style="margin-inline-end: 8px;"></i>היסטוריית תשלומים</h3></div>
                        <div class="table-container">
                            <table>
                                <thead><tr><th>תאריך תשלום</th><th>תיאור</th><th>אמצעי תשלום</th><th>סכום</th></tr></thead>
                                <tbody id="payments-table-body"><tr><td colspan="4" class="loading">טוען היסטוריית תשלומים...</td></tr></tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <div id="edit-details-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3>עריכת פרטים אישיים</h3>
                <button id="close-modal-btn" class="close-button">&times;</button>
            </div>
            <form id="edit-details-form">
                <div class="form-group">
                    <label for="edit-full-name">שם מלא</label>
                    <input type="text" id="edit-full-name" required>
                </div>
                <div class="form-group">
                    <label for="edit-phone">מספר טלפון</label>
                    <input type="tel" id="edit-phone" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">אימייל</label>
                    <input type="email" id="edit-email">
                </div>
                <div class="form-group">
                    <label for="edit-address">כתובת</label>
                    <input type="text" id="edit-address">
                </div>
                <div id="edit-details-error"></div>
                <div class="modal-footer">
                    <button type="button" id="cancel-edit-btn" class="button button-secondary">ביטול</button>
                    <button type="submit" id="save-edit-btn" class="button button-primary">
                        <span id="save-btn-text">שמירת שינויים</span>
                        <div id="save-spinner" class="spinner hidden"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (JSON.stringify(value) || "") + expires + "; path=/";
        }

        const ApiService = {
            API_ENDPOINT: '/app/api/unified_api.php',

            async request(action, method = 'GET', params = {}) {
                const url = new URL(this.API_ENDPOINT, window.location.origin);
                url.searchParams.append('action', action);
                const options = {  method, headers: { 'Content-Type': 'application/json' } };
                if (method === 'GET') {
                    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
                } else {
                    if (params.table) url.searchParams.append('table', params.table);
                    if (params.recordId) url.searchParams.append('id', params.recordId);
                    if (params.data) { options.body = JSON.stringify(params.data); }
                }
                const response = await fetch(url.toString(), options);
                const result = await response.json();
                if (!response.ok) { throw new Error(result.error?.message || `שגיאת שרת (${response.status})`); }
                return result;
            },
            
            async getRecords(table) { return this.request('get_records', 'GET', { table }); },

            async clientLogin(phone, pin) {
                const clientsResponse = await this.getRecords('Clients');
                if (!clientsResponse.records) { throw new Error('לא ניתן היה לטעון את רשימת הלקוחות.'); }
                const clientRecord = clientsResponse.records.find(record => {
                    const recordPhone = record.fields['Phone Number']?.replace(/\D/g, '');
                    const recordPin = record.fields['PIN Code'];
                    return recordPhone === phone && recordPin === pin;
                });
                if (clientRecord) { return { success: true, client: clientRecord }; } 
                else { throw new Error('פרטי התחברות שגויים'); }
            },

            async updateRecord(table, recordId, fields) {
                return this.request('update_record', 'PATCH', { table, recordId, data: { fields } });
            },
        };

        document.addEventListener('DOMContentLoaded', () => {
            let currentClient = null;
            const loginView = document.getElementById('login-view');
            const personalAreaView = document.getElementById('personal-area-view');
            const loginForm = document.getElementById('login-form');
            const phoneInput = document.getElementById('phone');
            const pinInput = document.getElementById('pin');
            const loginError = document.getElementById('login-error');
            const loginBtnText = document.getElementById('login-btn-text');
            const loginSpinner = document.getElementById('login-spinner');
            const loginButton = loginForm.querySelector('button[type="submit"]');
            const editModal = document.getElementById('edit-details-modal');
            const editDetailsBtn = document.getElementById('edit-details-btn');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-btn');
            const editForm = document.getElementById('edit-details-form');
            const saveBtnText = document.getElementById('save-btn-text');
            const saveSpinner = document.getElementById('save-spinner');
            const saveButton = document.getElementById('save-edit-btn');
            const editError = document.getElementById('edit-details-error');

            async function handleLogin(e) {
                e.preventDefault();
                loginError.textContent = '';
                const phone = phoneInput.value.replace(/\D/g, '');
                const pin = pinInput.value.trim();
                if (!phone || !pin) { loginError.textContent = 'יש למלא את כל השדות'; return; }
                setLoginLoading(true);
                try {
                    const result = await ApiService.clientLogin(phone, pin);
                    if (result.success && result.client) {
                        currentClient = result.client;
                        sessionStorage.setItem('loggedInClient', JSON.stringify(currentClient));
                        const userCookieData = { id: result.client.id, name: result.client.fields['Full Name'], type: 'client' };
                        setCookie('carwasher_user', userCookieData, 30);
                        showPersonalArea(currentClient);
                        setTimeout(() => window.location.reload(), 100);
                    }
                } catch (error) {
                    loginError.textContent = error.message;
                    setLoginLoading(false);
                }
            }
            
            function setLoginLoading(isLoading) {
                loginBtnText.classList.toggle('hidden', isLoading);
                loginSpinner.classList.toggle('hidden', !isLoading);
                loginButton.disabled = isLoading;
            }

            function showPersonalArea(client) {
                loginView.classList.add('hidden');
                personalAreaView.classList.remove('hidden');
                loadClientData(client);
            }

            async function loadClientData(client) {
                try {
                    renderHero(client.fields);
                    renderPersonalDetails(client.fields);
                    const [subsRes, bookingsRes] = await Promise.all([
                        ApiService.getRecords('ClientSubscriptions'),
                        ApiService.getRecords('Bookings')
                    ]);
                    const clientSubscription = subsRes.records?.find(s => s.fields['Client']?.includes(client.id));
                    const clientBookings = bookingsRes.records?.filter(b => b.fields['Client Link']?.includes(client.id));
                    renderSubscription(clientSubscription?.fields);
                    renderOrderHistory(clientBookings || []);
                    renderPaymentHistory(clientBookings || [], clientSubscription);
                } catch (error) {
                    console.error("Failed to load client data:", error);
                    personalAreaView.innerHTML = `<h1>שגיאה בטעינת הנתונים: ${error.message}</h1>`;
                }
            }
            
            function renderHero(clientFields) { document.getElementById('hero-title').textContent = `שלום, ${clientFields['Full Name']}`; }
            function renderPersonalDetails(clientFields) {
                document.getElementById('details-list-content').innerHTML = `
                    <li><strong>שם מלא:</strong> <span>${clientFields['Full Name']}</span></li>
                    <li><strong>מספר טלפון:</strong> <span>${clientFields['Phone Number']}</span></li>
                    <li><strong>אימייל:</strong> <span>${clientFields['Email'] || 'לא הוגדר'}</span></li>
                    <li><strong>כתובת:</strong> <span>${clientFields['Address'] || 'לא הוגדרה'}</span></li>
                    <li><strong>סוג לקוח:</strong> <span><span class="status-badge status-info">${clientFields['Client Type']}</span></span></li>`;
            }
            function renderSubscription(subFields) {
                const card = document.getElementById('subscriptionCard');
                if (!subFields) { card.style.display = 'none'; return; }
                card.style.display = 'flex';
                const total = parseInt(subFields['Total Washes'], 10), remaining = parseInt(subFields['Remaining Washes'], 10), used = total - remaining, progressPercent = total > 0 ? (used / total) * 100 : 0;
                document.getElementById('subscription-status-badge').textContent = subFields['Status'];
                document.getElementById('subscription-status-badge').className = `status-badge ${subFields['Status'] === 'פעיל' ? 'status-success' : 'status-danger'}`;
                document.getElementById('subscription-type').textContent = subFields['Subscription Type'];
                document.getElementById('subscription-start-date').textContent = `התחילה בתאריך: ${formatDate(subFields['Start Date'])}`;
                document.getElementById('subscription-progress-bar').style.width = `${progressPercent}%`;
                document.getElementById('subscription-used').textContent = `נוצלו: ${used}`;
                document.getElementById('subscription-remaining').textContent = `נותרו: ${remaining}`;
                document.getElementById('subscription-total').textContent = `סה"כ: ${total}`;
                const dealInfoContainer = document.getElementById('subscription-deal-info');
                const washCount = subFields['Payment_Wash_Count'], washValue = subFields['Payment_Wash_Value'], totalAmount = subFields['Payment_Total_Amount'];
                if (washCount && washValue && totalAmount) {
                    dealInfoContainer.innerHTML = `<ul class="details-list" style="margin-top: 20px;"><li><strong>פרטי ההסדר שלך:</strong></li><li><span>כמות שטיפות בהסדר:</span> <strong>${washCount}</strong></li><li><span>מחיר לשטיפה:</span> <strong>₪${washValue}</strong></li><li><span>סכום כולל לחידוש:</span> <strong>₪${totalAmount}</strong></li></ul>`;
                } else { dealInfoContainer.innerHTML = ''; }
            }
            function renderOrderHistory(bookings) {
                const tableBody = document.getElementById('orders-table-body');
                if (bookings.length === 0) { tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">לא נמצאו הזמנות.</td></tr>'; return; }
                bookings.sort((a, b) => new Date(b.fields.Date) - new Date(a.fields.Date));
                tableBody.innerHTML = bookings.map(b => `<tr><td>${formatDate(b.fields.Date)}</td><td>${b.fields.Time}</td><td>${b.fields['Number of Cars']}</td><td>₪${b.fields.Price}</td><td>${getStatusBadge(b.fields.Status)}</td></tr>`).join('');
            }
            function renderPaymentHistory(bookings, subscription) {
                const tableBody = document.getElementById('payments-table-body');
                let payments = [];
                bookings.filter(b => b.fields.Status === 'בוצע' && b.fields.Payment_Date).forEach(b => payments.push({ date: b.fields.Payment_Date, description: `תשלום עבור שטיפה (${b.fields['Number of Cars']} רכבים)`, method: b.fields.Payment_Method, amount: b.fields.Price }));
                if (subscription) { payments.push({ date: subscription.fields['Start Date'], description: `רכישת ${subscription.fields['Subscription Type']}`, method: 'הסדר תשלום', amount: subscription.fields['Payment_Total_Amount'] }); }
                if (payments.length === 0) { tableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;">לא נמצאו תשלומים.</td></tr>'; return; }
                payments.sort((a, b) => new Date(b.date) - new Date(a.date));
                tableBody.innerHTML = payments.map(p => `<tr><td>${formatDate(p.date)}</td><td>${p.description}</td><td>${p.method}</td><td>₪${p.amount}</td></tr>`).join('');
            }

            function openEditModal() {
                if (!currentClient) return;
                const fields = currentClient.fields;
                document.getElementById('edit-full-name').value = fields['Full Name'] || '';
                document.getElementById('edit-phone').value = fields['Phone Number'] || '';
                document.getElementById('edit-email').value = fields['Email'] || '';
                document.getElementById('edit-address').value = fields['Address'] || '';
                editError.textContent = '';
                editModal.classList.remove('hidden');
            }
            function closeEditModal() { editModal.classList.add('hidden'); }
            async function handleEditFormSubmit(e) {
                e.preventDefault();
                editError.textContent = '';
                setSaveLoading(true);
                const updatedFields = {
                    'Full Name': document.getElementById('edit-full-name').value,
                    'Phone Number': document.getElementById('edit-phone').value,
                    'Email': document.getElementById('edit-email').value,
                    'Address': document.getElementById('edit-address').value,
                };
                try {
                    const result = await ApiService.updateRecord('Clients', currentClient.id, updatedFields);
                    currentClient.fields = { ...currentClient.fields, ...result.fields };
                    sessionStorage.setItem('loggedInClient', JSON.stringify(currentClient));
                    renderPersonalDetails(currentClient.fields);
                    renderHero(currentClient.fields);
                    closeEditModal();
                } catch (error) {
                    editError.textContent = error.message;
                } finally { setSaveLoading(false); }
            }
            function setSaveLoading(isLoading) {
                saveBtnText.classList.toggle('hidden', isLoading);
                saveSpinner.classList.toggle('hidden', !isLoading);
                saveButton.disabled = isLoading;
            }

            function formatDate(d) { if (!d) return 'N/A'; const date = new Date(d); return `${String(date.getDate()).padStart(2, '0')}.${String(date.getMonth() + 1).padStart(2, '0')}.${date.getFullYear()}`; }
            function getStatusBadge(s) { let c = ''; switch (s) { case 'בוצע': case 'מאושר': c = 'status-success'; break; case 'ממתין לאישור': c = 'status-warning'; break; case 'בוטל': c = 'status-danger'; break; default: c = 'status-info'; } return `<span class="status-badge ${c}">${s}</span>`; }
            
            function initialize() {
                loginForm.addEventListener('submit', handleLogin);
                editDetailsBtn.addEventListener('click', openEditModal);
                closeModalBtn.addEventListener('click', closeEditModal);
                cancelEditBtn.addEventListener('click', closeEditModal);
                editModal.addEventListener('click', (e) => { if (e.target === editModal) closeEditModal(); });
                editForm.addEventListener('submit', handleEditFormSubmit);
                const savedClient = sessionStorage.getItem('loggedInClient');
                if (savedClient) {
                    currentClient = JSON.parse(savedClient);
                    showPersonalArea(currentClient);
                }
            }
            initialize();
        });
    </script>
</body>
</html>
<?php include_once __DIR__ . '/../shared/unified_footer.php'; ?>