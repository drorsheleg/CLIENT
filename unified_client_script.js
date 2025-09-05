/* ======================================================================
    Car Washer - UNIFIED CLIENT SCRIPT (ENHANCED WITH TABLE)
    Version: 3.3 - With Updated Booking History Table
====================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const App = {
        state: {
            currentUser: null,
            clientData: null,
            apiEndpoint: '/app/api/unified_api.php',
        },
        elements: {},

        init() {
            // Fetch all DOM elements at the start
            this.elements = {
                loginView: document.getElementById('client-login-view'),
                dashboardView: document.getElementById('client-dashboard-view'),
                loginForm: document.getElementById('client-login-form'),
                loginError: document.getElementById('client-login-error'),
                clientNameDisplay: document.getElementById('client-name-display'),
                metricGrid: document.getElementById('metric-grid'),
                subscriptionsContainer: document.getElementById('subscriptions-container'),
                editDetailsContainer: document.getElementById('edit-details-container'),
                bookingsHistoryList: document.getElementById('bookings-history-list'),
                noBookingsMsg: document.getElementById('no-bookings-msg'),
                dashboardLoader: document.getElementById('dashboard-loader'),
                dashboardContent: document.getElementById('dashboard-content'),
            };

            // Check for logged-in user and show the correct view
            try {
                const userCookie = window.carwasherAuth.getCookie('carwasher_user');
                if (userCookie) {
                    this.state.currentUser = JSON.parse(userCookie);
                    if (this.state.currentUser && this.state.currentUser.type === 'client') {
                        this.showDashboard();
                    } else {
                        this.showLogin();
                    }
                } else {
                    this.showLogin();
                }
            } catch (e) {
                console.error('Error parsing user cookie:', e);
                this.showLogin();
            }

            // Add event listener for the login form
            this.elements.loginForm?.addEventListener('submit', (e) => this.handleLogin(e));
        },

        async apiRequest(action, method = 'GET', params = {}) {
            const apiEndpoint = this.state.apiEndpoint || '/app/api/unified_api.php';
            
            let url = new URL(apiEndpoint, window.location.origin);
            url.searchParams.append('action', action);
            
            const options = { 
                method, 
                headers: { 'Content-Type': 'application/json' } 
            };

            if (method === 'GET') {
                Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
            } else { // POST, PATCH, DELETE
                if (params.table) url.searchParams.append('table', params.table);
                if (params.recordId) url.searchParams.append('id', params.recordId);
                if (params.data) {
                    options.body = JSON.stringify(params.data);
                }
            }

            try {
                const response = await fetch(url.toString(), options);
                const text = await response.text();
                
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Response is not JSON:', text);
                    throw new Error('Server returned invalid response');
                }
                
                if (!response.ok) {
                    throw new Error(result.error?.message || `שגיאת שרת (${response.status})`);
                }
                if (result.success === false) {
                    throw new Error(result.error?.message || 'שגיאה מהשרת');
                }
                return result;
            } catch (error) {
                console.error(`API Error on action '${action}':`, error);
                throw error;
            }
        },

        showLogin() {
            this.elements.loginView?.classList.remove('hidden');
            this.elements.dashboardView?.classList.add('hidden');
        },

        async showDashboard() {
            this.elements.loginView?.classList.add('hidden');
            this.elements.dashboardView?.classList.remove('hidden');
            
            if (this.elements.clientNameDisplay && this.state.currentUser) {
                this.elements.clientNameDisplay.textContent = this.state.currentUser.name || '';
            }

            try {
                const clientResult = await this.apiRequest('get_records', 'GET', { 
                    table: 'Clients',
                    id: this.state.currentUser.id 
                });
                
                if (clientResult && clientResult.fields) {
                    this.state.currentUser = {
                        ...this.state.currentUser,
                        address: clientResult.fields['Address'] || '',
                        city: clientResult.fields['City'] || ''
                    };
                }
                
                const result = await this.apiRequest('get_client_dashboard', 'GET', { 
                    clientId: this.state.currentUser.id 
                });
                this.state.clientData = result.data;
                
                this.renderMetrics();
                this.renderSubscriptions();
                this.renderBookingHistory();
                this.renderEditDetailsForm();

                this.elements.dashboardLoader?.classList.add('hidden');
                this.elements.dashboardContent?.classList.remove('hidden');

            } catch (error) {
                console.error('Dashboard load error:', error);
                if (this.elements.dashboardLoader) {
                    this.elements.dashboardLoader.innerHTML = `<div class="text-center p-8 bg-red-50 text-red-700 rounded-lg"><p><strong>אופס, התרחשה שגיאה</strong></p><p>לא הצלחנו לטעון את נתוני החשבון שלך.</p></div>`;
                }
            }
        },
        
        async handleLogin(e) {
            e.preventDefault();
            this.elements.loginError.textContent = '';
            
            const phone = this.elements.loginForm.querySelector('#client-phone').value;
            const pin = this.elements.loginForm.querySelector('#client-pin').value;

            if (!phone || !pin) {
                this.elements.loginError.textContent = 'נא למלא טלפון וקוד PIN';
                return;
            }

            try {
                const result = await this.apiRequest('client_login', 'POST', {
                    data: { phone, pin }
                });

                if (result.success && result.user) {
                    window.carwasherAuth.updateLoginState({
                        id: result.user.id,
                        name: result.user.fields['Full Name'],
                        phone: result.user.fields['Phone Number'],
                        type: 'client',
                        clientType: result.user.fields['Client Type']
                    });
                }
            } catch (error) {
                console.error('Login error:', error);
                this.elements.loginError.textContent = 'פרטי התחברות שגויים או שגיאת תקשורת.';
            }
        },

        renderMetrics() {
            const bookings = this.state.clientData?.bookings || [];
            const futureBookings = bookings.filter(b => 
                new Date(b.fields.Date) >= new Date() && b.fields.Status !== 'בוטל'
            );
            const washesDone = bookings.filter(b => 
                new Date(b.fields.Date) < new Date() && b.fields.Status === 'מאושר'
            ).length;
            const nextBooking = futureBookings.sort((a,b) => 
                new Date(a.fields.Date) - new Date(b.fields.Date)
            )[0];
            
            if (this.elements.metricGrid) {
                this.elements.metricGrid.innerHTML = `
                    <div class="metric-card">
                        <div class="metric-icon"><i class="fas fa-calendar-day"></i></div>
                        <div>
                            <div class="metric-title">הזמנות עתידיות</div>
                            <div class="metric-value">${futureBookings.length}</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon"><i class="fas fa-car-side"></i></div>
                        <div>
                            <div class="metric-title">שטיפות שבוצעו</div>
                            <div class="metric-value">${washesDone}</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="metric-title">ההזמנה הקרובה</div>
                            <div class="metric-value">${nextBooking ? new Date(nextBooking.fields.Date).toLocaleDateString('he-IL') : 'אין'}</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon"><i class="fas fa-user-tag"></i></div>
                        <div>
                            <div class="metric-title">סוג לקוח</div>
                            <div class="metric-value">${this.state.currentUser?.clientType || 'רגיל'}</div>
                        </div>
                    </div>
                `;
            }
        },

        renderSubscriptions() {
            const subs = this.state.clientData?.subscriptions || [];
            let content = `<div class="card-header"><i class="fas fa-id-card"></i><h3>מנויים וכרטיסיות</h3></div>`;
            
            if (subs.length === 0) {
                content += `<p class="mt-4 text-gray-600">אין לך מנויים או כרטיסיות פעילים.</p>`;
            } else {
                subs.forEach(sub => {
                    const fields = sub.fields;
                    const total = parseInt(fields['Total Washes'] || 0);
                    const used = parseInt(fields['Used Washes'] || 0);
                    const remaining = Math.max(0, total - used);
                    const remainingPercentage = total > 0 ? (remaining / total) * 100 : 0;
                    
                    let progressColor = '#05bbff';
                    if (remainingPercentage <= 20) progressColor = '#dc3545';
                    else if (remainingPercentage <= 40) progressColor = '#ffc107';

                    let renewalHtml = '';
                    if (remaining === 0) {
                        const payboxPhone = '054-9952960';
                        const renewalLink = 'https://pay.grow.link/cd84ac7b14e593cb4522049c4c9742cd-MTk2MjQ2OQ';
                        renewalHtml = `
                            <div class="renewal-message">
                                <h4><i class="fas fa-sync-alt"></i> המנוי הסתיים!</h4>
                                <p>ניתן לחדש בהעברה לפייבוקס למספר:<br><strong>${payboxPhone}</strong><br>או דרך אמצעי תשלום נוספים בלינק הבא:</p>
                                <a href="${renewalLink}" class="btn btn-primary" target="_blank" rel="noopener">לחידוש המנוי</a>
                            </div>
                        `;
                    }
                    
                    content += `
                        <div class="subscription-item">
                            <h4 class="subscription-type">${fields['Subscription Type']}</h4>
                            
                            <div class="subscription-visual">
                                <div class="circular-progress" style="--progress: ${remainingPercentage}%; --color: ${progressColor};">
                                    <div class="progress-inner">
                                        <div class="progress-value">${remaining}</div>
                                        <div class="progress-label">נותרו</div>
                                    </div>
                                </div>
                                
                                <div class="subscription-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">נוצלו</span>
                                        <span class="stat-value">${used}</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">סה"כ</span>
                                        <span class="stat-value">${total}</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">נותרו</span>
                                        <span class="stat-value" style="color: ${progressColor}; font-weight: bold;">${remaining}</span>
                                    </div>
                                </div>
                            </div>
                            
                            ${renewalHtml}
                        </div>
                    `;
                });
            }
            
            if (this.elements.subscriptionsContainer) {
                this.elements.subscriptionsContainer.innerHTML = content;
            }
        },

        renderBookingHistory() {
            const bookings = (this.state.clientData?.bookings || []).sort((a,b) => 
                new Date(b.fields.Date) - new Date(a.fields.Date)
            );
            
            if (bookings.length === 0) { 
                this.elements.noBookingsMsg?.classList.remove('hidden');
                if (this.elements.bookingsHistoryList) {
                    this.elements.bookingsHistoryList.innerHTML = '';
                }
                return;
            }
            
            this.elements.noBookingsMsg?.classList.add('hidden');
            
            if (this.elements.bookingsHistoryList) {
                // יצירת טבלה כמו בתמונה
                let tableHTML = `
                    <div class="bookings-table">
                        <div class="bookings-table-header">
                            <div>טלפון</div>
                            <div>תאריך</div>
                            <div>שעה</div>
                            <div>כתובת</div>
                            <div>סטטוס</div>
                            <div>פעולות</div>
                        </div>
                        <div class="bookings-table-body">
                `;
                
                bookings.forEach(b => {
                    const booking = b.fields;
                    const isPast = new Date(booking.Date) < new Date();
                    const canEdit = !isPast && booking.Status !== 'בוטל';
                    const statusClass = (booking.Status || 'ממתין').toLowerCase().replace(/\s+/g, '-');
                    
                    // קבל טלפון
                    const phone = booking['Phone'] || 
                                 booking['Phone Number'] || 
                                 booking['Client Phone'] ||
                                 this.state.currentUser?.phone || 
                                 '';
                    
                    // קבל כתובת או אזור
                    const address = booking['Address'] || 
                                   booking['Location'] || 
                                   booking['Area'] ||
                                   booking['City'] ||
                                   'לא צוין';
                    
                    // קבל מספר רכבים/רביעים (מוצג כמספר בלבד)
                    const quarters = booking['Number of Quarters'] || 
                                   booking['Quarters'] || 
                                   booking['Duration'] ||
                                   booking['Number of Cars'] ||
                                   '1';
                    
                    // פורמט התאריך והשעה
                    const dateObj = new Date(booking.Date);
                    const formattedDate = dateObj.toLocaleDateString('he-IL', {
                        day: '2-digit',
                        month: '2-digit',
                        year: '2-digit'
                    });
                    
                    tableHTML += `
                        <div class="booking-row ${isPast ? 'past' : 'upcoming'}">
                            <div class="booking-phone">${phone}</div>
                            <div class="booking-date">${formattedDate}</div>
                            <div class="booking-time">${booking.Time || ''}</div>
                            <div class="booking-address">
                                ${address}
                                ${quarters > 1 ? `<span class="quarters-badge">${quarters}</span>` : ''}
                            </div>
                            <div>
                                <span class="booking-status status-${statusClass}">${booking.Status || 'ממתין'}</span>
                            </div>
                            <div class="booking-actions-group">
                                ${canEdit ? `
                                    <button class="action-btn action-btn-edit" data-id="${b.id}" title="עריכה">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                ` : ''}
                                <button class="action-btn action-btn-cancel" data-id="${b.id}" title="ביטול">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                tableHTML += `
                        </div>
                    </div>
                `;
                
                this.elements.bookingsHistoryList.innerHTML = tableHTML;
                
                // הוסף event listeners
                this.elements.bookingsHistoryList.querySelectorAll('.action-btn-edit').forEach(btn => 
                    btn.addEventListener('click', e => this.editBooking(e.currentTarget.dataset.id))
                );
                
                this.elements.bookingsHistoryList.querySelectorAll('.action-btn-cancel').forEach(btn => 
                    btn.addEventListener('click', e => this.cancelBooking(e.currentTarget.dataset.id))
                );
            }
        },

        renderEditDetailsForm() {
            if (this.elements.editDetailsContainer) {
                this.elements.editDetailsContainer.innerHTML = `
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        <h3>עדכון פרטים אישיים</h3>
                    </div>
                    <form id="edit-details-form" class="mt-4">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">טלפון</label>
                                <input type="tel" id="edit-phone" class="form-input" 
                                       value="${this.state.currentUser?.phone || ''}" placeholder="05XXXXXXXX">
                            </div>
                            <div class="form-group">
                                <label class="form-label">קוד PIN חדש</label>
                                <input type="password" id="edit-pin" class="form-input" 
                                       placeholder="4 ספרות" maxlength="4">
                            </div>
                            <div class="form-group">
                                <label class="form-label">יישוב</label>
                                <input type="text" id="edit-city" class="form-input" 
                                       value="${this.state.currentUser?.city || ''}" placeholder="עיר מגורים">
                            </div>
                            <div class="form-group">
                                <label class="form-label">כתובת</label>
                                <input type="text" id="edit-address" class="form-input" 
                                       value="${this.state.currentUser?.address || ''}" placeholder="רחוב ומספר">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full mt-4">
                            <i class="fas fa-save"></i> שמור שינויים
                        </button>
                    </form>
                `;
                
                document.getElementById('edit-details-form')?.addEventListener('submit', 
                    e => this.handleUpdateDetails(e)
                );
            }
        },

        async cancelBooking(bookingId) {
            if (!confirm('האם אתה בטוח שברצונך לבטל את ההזמנה?')) return;
            
            try {
                await this.apiRequest('update_record', 'PATCH', { 
                    table: 'Bookings', 
                    recordId: bookingId, 
                    data: { fields: { Status: 'בוטל' } } 
                });
                
                if (window.showToast) {
                    window.showToast('ההזמנה בוטלה בהצלחה', 'success');
                }
                this.showDashboard();
            } catch(e) {
                console.error('Cancel booking error:', e);
                if (window.showToast) {
                    window.showToast('שגיאה בביטול ההזמנה', 'error');
                }
            }
        },

        async editBooking(bookingId) {
            const booking = this.state.clientData.bookings.find(b => b.id === bookingId);
            if (!booking) return;
            
            const editUrl = `/app/booking/?edit=${bookingId}&date=${booking.fields.Date}&time=${booking.fields.Time}`;
            window.location.href = editUrl;
        },

        async handleUpdateDetails(e) {
            e.preventDefault();
            
            const phone = document.getElementById('edit-phone').value;
            const pin = document.getElementById('edit-pin').value;
            const city = document.getElementById('edit-city').value;
            const address = document.getElementById('edit-address').value;
            
            const fieldsToUpdate = { 
                'Phone Number': phone,
                'City': city,
                'Address': address
            };
            
            if (pin && pin.length === 4) {
                fieldsToUpdate['PIN Code'] = pin;
            }
            
            try {
                await this.apiRequest('update_record', 'PATCH', { 
                    table: 'Clients', 
                    recordId: this.state.currentUser.id, 
                    data: { fields: fieldsToUpdate } 
                });
                
                if (window.showToast) {
                    window.showToast('הפרטים עודכנו בהצלחה!', 'success');
                }
                
                const updatedUser = {
                    ...this.state.currentUser, 
                    phone: phone,
                    city: city,
                    address: address
                };
                window.carwasherAuth.setCookie('carwasher_user', JSON.stringify(updatedUser), 30);
                this.state.currentUser = updatedUser;
            } catch(e) {
                console.error('Update details error:', e);
                if (window.showToast) {
                    window.showToast('שגיאה בעדכון הפרטים', 'error');
                }
            }
        }
    };

    // Initialize the app
    App.init();
    
    // Make App available globally for debugging
    window.App = App;
});