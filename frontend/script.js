const API_URL = 'http://localhost/Project1MNM/backend/api.php';

let editingId = null;
let currentSearch = '';

// Load contacts on page load
document.addEventListener('DOMContentLoaded', () => {
    loadContacts();
    
    // Event listeners
    document.getElementById('searchInput').addEventListener('input', (e) => {
        currentSearch = e.target.value;
        loadContacts(currentSearch);
    });
});

// Get all contacts or search
async function loadContacts(search = '') {
    try {
        let url = API_URL;
        if (search) {
            url += '?q=' + encodeURIComponent(search);
        }
        
        const response = await fetch(url);
        const contacts = await response.json();
        
        displayContacts(contacts);
    } catch (error) {
        console.error('Error loading contacts:', error);
        alert('Lỗi khi tải danh bạ!');
    }
}

// Display contacts in table
function displayContacts(contacts) {
    const tableBody = document.getElementById('tableBody');
    
    if (contacts.length === 0) {
        tableBody.innerHTML = '<tr class="no-data"><td colspan="4">Không có liên hệ nào</td></tr>';
        return;
    }
    
    tableBody.innerHTML = contacts.map(contact => `
        <tr>
            <td>${contact.id}</td>
            <td>${contact.name}</td>
            <td>${contact.phone}</td>
            <td>
                <div class="actions">
                    <button class="edit-btn" onclick="openEditModal(${contact.id}, '${contact.name}', '${contact.phone}')">Sửa</button>
                    <button class="delete-btn" onclick="deleteContact(${contact.id})">Xóa</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Add new contact
async function addContact() {
    const name = document.getElementById('nameInput').value.trim();
    const phone = document.getElementById('phoneInput').value.trim();
    
    if (!name || !phone) {
        alert('Vui lòng nhập tên và số điện thoại!');
        return;
    }
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, phone })
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('nameInput').value = '';
            document.getElementById('phoneInput').value = '';
            loadContacts(currentSearch);
            alert('Thêm liên hệ thành công!');
        } else {
            alert('Lỗi: ' + (result.error || 'Không thể thêm liên hệ'));
        }
    } catch (error) {
        console.error('Error adding contact:', error);
        alert('Lỗi khi thêm liên hệ!');
    }
}

// Open edit modal
function openEditModal(id, name, phone) {
    editingId = id;
    document.getElementById('editNameInput').value = name;
    document.getElementById('editPhoneInput').value = phone;
    document.getElementById('editModal').classList.add('show');
}

// Close edit modal
function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
    editingId = null;
}

// Save edited contact
async function saveEdit() {
    const name = document.getElementById('editNameInput').value.trim();
    const phone = document.getElementById('editPhoneInput').value.trim();
    
    if (!name || !phone) {
        alert('Vui lòng nhập tên và số điện thoại!');
        return;
    }
    
    try {
        const response = await fetch(API_URL, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: editingId, name, phone })
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeEditModal();
            loadContacts(currentSearch);
            alert('Cập nhật liên hệ thành công!');
        } else {
            alert('Lỗi: ' + (result.error || 'Không thể cập nhật liên hệ'));
        }
    } catch (error) {
        console.error('Error updating contact:', error);
        alert('Lỗi khi cập nhật liên hệ!');
    }
}

// Delete contact
async function deleteContact(id) {
    if (!confirm('Bạn chắc chắn muốn xóa liên hệ này?')) {
        return;
    }
    
    try {
        const response = await fetch(API_URL + '?id=' + id, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadContacts(currentSearch);
            alert('Xóa liên hệ thành công!');
        } else {
            alert('Lỗi: ' + (result.error || 'Không thể xóa liên hệ'));
        }
    } catch (error) {
        console.error('Error deleting contact:', error);
        alert('Lỗi khi xóa liên hệ!');
    }
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
    const modal = document.getElementById('editModal');
    if (e.target === modal) {
        closeEditModal();
    }
});
