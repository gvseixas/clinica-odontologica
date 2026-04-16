// Shared utilities
function smoothScroll() {
    document.querySelectorAll('a[href^=\"#\"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Init appointment form (old mock)
function initAppointmentFormOld() {
    const appointmentForm = document.getElementById('appointment-form');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const nome = document.getElementById('paciente-nome').value.trim();
            const telefone = document.getElementById('paciente-telefone').value.trim();
            const email = document.getElementById('paciente-email').value.trim();
            const servico = document.getElementById('tipo-servico').value;
            const data = document.getElementById('data-agendamento').value;
            const hora = document.getElementById('hora-agendamento').value;
            
        if (!nome || !telefone || !email || !servico || !data || !hora) {
    alert('Por favor, preencha todos os campos obrigatórios (*).');
    return;
}

const nomesServicos = {
    clareamento: 'Clareamento Dental',
    implantes: 'Implantes',
    limpeza: 'Limpeza Profissional',
    ortodontia: 'Ortodontia',
    consulta: 'Consulta Geral'
};

const resumo = `✅ Consulta agendada com sucesso!\n\n` +
               `Paciente: ${nome}\n` +
               `Telefone: ${telefone}\n` +
               `Serviço: ${nomesServicos[servico]}\n` +
               `Data/Hora: ${data} às ${hora}`;
            
            alert(summary);
            this.reset();
        });

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('appointment-date').min = tomorrow.toISOString().split('T')[0];
    }
}

// API base
const API_BASE = 'api/';

// API functions
async function apiLogin(email) {
    try {
        const response = await fetch(`${API_BASE}login.php`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({email})
        });
        const data = await response.json();
        if (data.success) {
            sessionStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = 'dashboard.html';
        } else {
            alert(data.error || 'Erro no login');
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
    }
}

async function apiRegister(formData) {
    try {
        const response = await fetch(`${API_BASE}register.php`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        const data = await response.json();
        if (data.success) {
            alert('Cadastro realizado com sucesso! Faça login.');
            window.location.href = 'login.html';
        } else {
            alert(data.error || 'Erro no cadastro');
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
    }
}

async function apiLogout() {
    sessionStorage.removeItem('user');
    window.location.href = 'index.html';
}

async function apiAppointment(formData) {
    try {
        const response = await fetch(`${API_BASE}appointments.php`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        const data = await response.json();
        if (data.success) {
            alert(data.message);
            document.querySelector('#appointment-form, .appointment-form').reset();
        } else {
            alert(data.error || 'Erro no agendamento');
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
    }
}

async function apiContact(formData) {
    try {
        const response = await fetch(`${API_BASE}contact.php`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        const data = await response.json();
        if (data.success) {
            alert(data.message);
            document.querySelector('.contact-form').reset();
        } else {
            alert(data.error || 'Erro ao enviar');
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
    }
}

// Auth utils
function getCurrentUser() {
    const stored = sessionStorage.getItem('user');
    return stored ? JSON.parse(stored) : null;
}

function requireAuth() {
    if (!getCurrentUser()) {
        window.location.href = 'login.html';
    }
}

function showUserInfo() {
    const user = getCurrentUser();
    const userInfo = document.getElementById('user-info');
    if (userInfo && user) {
        userInfo.textContent = `Olá, ${user.name}!`;
    }
}

// Form inits
function initLoginForm() {
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value.trim();
            if (email) {
                apiLogin(email);
            } else {
                alert('Por favor, insira seu email.');
            }
        });
    }
}

function initRegisterForm() {
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const nome = document.getElementById('register-name').value.trim();
            const email = document.getElementById('register-email').value.trim();
            const telefone = document.getElementById('register-phone').value.trim();
            if (nome && email && telefone) {
                apiRegister({nome: nome, email: email, telefone: telefone});
            } else {
                alert('Preencha todos os campos.');
            }
        });
    }
}

function initContactFormAPI() {
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                message: document.getElementById('message').value.trim()
            };
            await apiContact(formData);
        });
    }
}

function initAppointmentFormAPI() {
    const appointmentForm = document.getElementById('appointment-form') || document.querySelector('.appointment-form');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = {
               nome: document.getElementById('patient-name').value.trim(),
                telefone: document.getElementById('patient-phone').value.trim(),
                email: document.getElementById('patient-email').value.trim(),
                servico: document.getElementById('service-type').value,
                data_agendamento: document.getElementById('appointment-date').value,
                hora_agendamento: document.getElementById('appointment-time').value,
                observacoes: document.getElementById('notes').value.trim()
            };
            apiAppointment(formData);
        });

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('appointment-date').min = tomorrow.toISOString().split('T')[0];
    }
}

// Unified DOM ready
document.addEventListener('DOMContentLoaded', function() {
    smoothScroll();
    initContactFormAPI();
    initAppointmentFormAPI();
    initAppointmentFormOld(); // Keep old mock for compatibility
    initLoginForm();
    initRegisterForm();
    
    if (window.location.pathname.includes('dashboard')) {
        requireAuth();
        showUserInfo();
    }
});
