<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ApiRest-Test | Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">
    <div class="container">
        <h2 class="my-4">Users</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#registerModal">New Register</button>
        <div id="user-list"></div>
    </div>

    <!-- Register modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="register-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form">
                        <input type="hidden" id="edit-user-id">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const auth = localStorage.getItem('auth');
            if (!auth) {
                window.location.href = '/';
                return;
            }

            function fetchUsers() {
                axios.get('/api/users', {
                    headers: {
                        'Authorization': `Basic ${auth}`
                    }
                })
                .then(response => {
                    const users = response.data;
                    const userList = document.getElementById('user-list');
                    userList.innerHTML = '';

                    if (Array.isArray(users)) {
                        const table = document.createElement('table');
                        table.className = 'table table-striped';
                        const thead = document.createElement('thead');
                        thead.innerHTML = `
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        `;
                        table.appendChild(thead);

                        const tbody = document.createElement('tbody');
                        users.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${user.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${user.id}">Delete</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                        table.appendChild(tbody);
                        userList.appendChild(table);
                    } else {
                        console.error('Unexpected response format:', users);
                    }
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
            }

            fetchUsers();

            document.getElementById('register-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                axios.post('/api/users/register', {
                    name: name,
                    email: email,
                    password: password
                }, {
                    headers: {
                        'Authorization': `Basic ${auth}`
                    }
                })
                .then(response => {
                    alert('User registered successfully');
                    fetchUsers();
                    document.getElementById('register-form').reset();
                    const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                    registerModal.hide();
                })
                .catch(error => {
                    console.error('Error registering user:', error);
                });
            });

            document.getElementById('user-list').addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-btn')) {
                    const userId = event.target.getAttribute('data-id');
                    axios.delete(`/api/users/${userId}`, {
                        headers: {
                            'Authorization': `Basic ${auth}`
                        }
                    })
                    .then(response => {
                        alert('User deleted successfully');
                        fetchUsers();
                    })
                    .catch(error => {
                        console.error('Error deleting user:', error);
                    });
                } else if (event.target.classList.contains('edit-btn')) {
                    const userId = event.target.getAttribute('data-id');
                    const userName = event.target.getAttribute('data-name');
                    const userEmail = event.target.getAttribute('data-email');

                    document.getElementById('edit-user-id').value = userId;
                    document.getElementById('edit-name').value = userName;
                    document.getElementById('edit-email').value = userEmail;

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();

                }
            });
            document.getElementById('edit-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const userId = document.getElementById('edit-user-id').value;
                const name = document.getElementById('edit-name').value;
                const email = document.getElementById('edit-email').value;

                axios.put(`/api/users/${userId}`, {
                    name: name,
                    email: email
                }, {
                    headers: {
                        'Authorization': `Basic ${auth}`
                    }
                })
                .then(response => {
                    alert('User updated successfully');
                    fetchUsers();
                    document.getElementById('edit-form').reset();
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    editModal.hide();
                })
                .catch(error => {
                    console.error('Error updating user:', error);
                });
            });
        });
    </script>
</body>

</html>
