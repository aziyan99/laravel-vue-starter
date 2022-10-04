<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3>Pengguna</h3>
                <small>Semua data pengguna yang tersimpan didalam sistem</small>
            </div>
            <div class="card-body">
                <div class="text-right">
                    <button type="button" @click="showBulkDestroyUserModal" class="btn btn-danger"
                        v-if="$can('pengguna.hapus')" v-show="deleteUsers.length > 0">
                        <b>Hapus yang dipilih</b>
                    </button>
                    <button type="button" disabled class="btn btn-danger" v-if="$can('pengguna.hapus')" v-show="deleteUsers.length < 1">
                        <b>Hapus yang dipilih</b>
                    </button>
                    <button class="btn btn-primary" @click="showCreateUserModal" v-if="$can('pengguna.tambah')"><b>Tambah Pengguna</b>
                    </button>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <select style="width: 40px;" v-model="count">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="999999999">all</option>
                        </select>
                    </div>
                    <div class="col-lg-6 text-right">
                        <input style="width: 300px;" v-model="keywords" />
                    </div>
                </div>
                <table-loading class="mt-3" v-show="isLoading"></table-loading>
                <div class="table-responsive" v-show="!isLoading">
                    <table class="table table-hover table-bordered table-sm mt-3">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" v-model="allSelect" @click="selectAll" />
                                </th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Gambar</th>
                                <th>Role</th>
                                <th>Tanggal ditambahkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td v-if="user.id !== loggedUserId"><input type="checkbox" v-model="deleteUsers"
                                        :value="`${user.id}`" /></td>
                                <td v-else></td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    <img :src="assetUri + user.image" alt="img" width="30">
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ user.role.toString() }}
                                    </span>
                                </td>
                                <td>{{ user.created_at | formatDate }}</td>
                                <td v-if="user.id !== loggedUserId">
                                    <button @click="showEditUserModal(user)" class="btn btn-default btn-sm" v-if="$can('pengguna.ubah')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="showDetailUserModal(user)" class="btn btn-default btn-sm" v-if="$can('pengguna.lihat')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button @click="showDestroyUserModal(user)" class="btn btn-default btn-sm" v-if="$can('pengguna.hapus')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                                <td v-else>
                                    <span class="text-muted text-center"><i>Current Login</i></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination align="right" :data="users" @pagination-change-page="getUsers">
                    <span slot="prev-nav">Prev</span>
                    <span slot="next-nav">Next</span>
                </pagination>
            </div>
            <!-- User form -->
            <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-show="!editMode">Tambah Pengguna Baru</h5>
                            <h5 class="modal-title" v-show="editMode">Ubah Pengguna</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form @submit.prevent="editMode ? updateUser() : storeUser()" @keydown="form.onKeydown($event)">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" v-model="form.name"
                                                :class="{'is-invalid': form.errors.has('name')}">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" v-model="form.email"
                                                :class="{'is-invalid': form.errors.has('email')}">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label>No.Hp</label>
                                            <input type="text" class="form-control" v-model="form.phone_number"
                                                :class="{'is-invalid': form.errors.has('phone_number')}">
                                            <has-error :form="form" field="phone_number"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <div class="alert alert-info">
                                                Password dibuat otomatis sama dengan email.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Hak akses</label>
                                            <v-select multiple v-model="form.role" :options="roles"
                                                :class="{'is-invalid': form.errors.has('role')}"></v-select>
                                            <has-error :form="form" field="role"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="address" cols="30" rows="4" class="form-control"
                                                v-model="form.address"
                                                :class="{'is-invalid': form.errors.has('address')}"></textarea>
                                            <has-error :form="form" field="address"></has-error>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <b>Batal</b>
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <b>Simpan</b>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete User form -->
            <div class="modal fade" id="destroyUserModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-show="!bulkDeleteMode">Hapus Pengguna</h5>
                            <h5 class="modal-title" v-show="bulkDeleteMode">Hapus Masal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                Hapus pengguna ini?
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <b>Batal</b>
                            </button>
                            <button type="button" class="btn btn-danger" @click="destroyUser" v-show="!bulkDeleteMode">
                                <b>Hapus</b>
                            </button>
                            <button type="button" class="btn btn-danger" @click="bulkDestroy" v-show="bulkDeleteMode">
                                <b>Hapus</b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User detail -->
            <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Pengguna</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <td rowspan="2" class="text-center">
                                            <img width="100" height="100" :src="assetUri + detailUser.image" class="img-thumbnail" alt="img" v-if="detailUser.image !== ''">
                                        </td>
                                        <td><b class="align-middle">{{ detailUser.name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-primary">{{ detailUser.role.toString() }}</span></td>
                                    </tr>
                                </table>
                                <table class="mt-2 table table-hover table-bordered">
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ detailUser.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>No.Hp</th>
                                        <td>{{ detailUser.phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ detailUser.address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal ditambahkan</th>
                                        <td>{{ detailUser.created_at | formatDate }}</td>
                                    </tr>
                                </table>
                                <div class="text-right">
                                    <button @click="resetPassword(detailUser.id)"  class="btn btn-danger btn-sm"  v-if="$can('pengguna.ubah')">
                                        <b>Reset kata sandi</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <b>Tutup</b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    phone_number: '',
                    address: '',
                    role: [],
                }),
                detailUser:{
                    id: '',
                    name: '',
                    email: '',
                    phone_number: '',
                    address: '',
                    role: [],
                    image: '',
                    created_at: '',
                },
                roles: [],
                editMode: false,
                users: {},
                keywords: null,
                count: 5,
                deleteUsers: [],
                allSelect: false,
                bulkDeleteMode: false,
                isLoading: false,
                assetUri: '/storage/',
                loggedUserId: '',
            }
        },
        watch: {
            keywords(after, before) {
                this.getUsers();
            },
            count(after, before) {
                this.getUsers();
            }
        },
        methods: {
            getUsers(page = 1) {
                this.isLoading = true;
                this.$Progress.start();
                axios.get(`${RESTURIV1}/users?page=${page}`, {
                        params: {
                            keywords: this.keywords,
                            count: this.count
                        }
                    })
                    .then(res => {
                        this.roles = [];
                        this.isLoading = false;
                        this.$Progress.finish();
                        this.users = res.data.users;
                        res.data.roles.map(role => {
                            this.roles.push(role.name);
                        });
                        this.loggedUserId = res.data.logged_user;
                    }).catch(err => {
                        this.isLoading = true;
                        this.$Progress.finish();
                    });
            },
            showCreateUserModal() {
                this.editMode = false;
                this.resetForm();
                $('#userModal').modal('show');
            },
            storeUser() {
                this.$Progress.start();
                this.form.post(`${RESTURIV1}/users`)
                    .then(() => {
                        this.$Progress.finish();
                        $('#userModal').modal('hide');
                        this.$toasted.success('Pengguna berhasil disimpan');
                        Fire.$emit('DataUpdated');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Pengguna gagal disimpan');
                    });
            },
            showEditUserModal(user) {
                this.editMode = true;
                this.resetForm();
                this.form.fill(user);
                this.form.role = user.role;
                $('#userModal').modal('show');
            },
            updateUser() {
                this.$Progress.start();
                this.form.put(`${RESTURIV1}/users/${this.form.id}`)
                    .then(() => {
                        this.$Progress.finish();
                        $('#userModal').modal('hide');
                        this.$toasted.success('Pengguna berhasil diubah');
                        Fire.$emit('DataUpdated');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Pengguna gagal diubah');
                    });
            },
            showDestroyUserModal(user) {
                this.bulkDeleteMode = false;
                $('#destroyUserModal').modal('show');
                this.form.fill(user);
            },
            destroyUser() {
                this.$Progress.finish();
                axios.delete(`${RESTURIV1}/users/${this.form.id}`)
                    .then(() => {
                        this.$Progress.finish();
                        this.resetForm();
                        $('#destroyUserModal').modal('hide');
                        this.$toasted.success('Pengguna berhasil dihapus');
                        Fire.$emit('DataUpdated');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.success('Pengguna gagal dihapus');
                    });
            },
            showDetailUserModal(user){
                $('#detailModal').modal('show');
                this.detailUser.name = user.name;
                this.detailUser.email = user.email;
                this.detailUser.address = user.address;
                this.detailUser.phone_number = user.phone_number;
                this.detailUser.image = user.image;
                this.detailUser.created_at = user.created_at;
                this.detailUser.role = user.role;
                this.detailUser.id = user.id;
            },
            selectAll() {
                if (this.allSelect == false) {
                    this.allSelect = true;
                    this.users.data.forEach(user => {
                        if (this.loggedUserId !== user.id) {
                            this.deleteUsers.push(user.id);
                        }
                    });
                } else {
                    this.allSelect = false;
                    this.deleteUsers = [];
                }
            },
            showBulkDestroyUserModal() {
                this.bulkDeleteMode = true;
                $('#destroyUserModal').modal('show');
            },
            bulkDestroy() {
                this.$Progress.start();
                const data = {
                    'id': this.deleteUsers
                }
                axios.post(`${RESTURIV1}/users/bulkdelete`, data)
                    .then(res => {
                        this.$Progress.finish();
                        Fire.$emit('DataUpdated');
                        $('#destroyUserModal').modal('hide');
                        this.$toasted.success('Pengguna berhasil dihapus');
                        this.deleteUsers = [];
                        this.allSelect = false;
                    }).catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Pengguna gagal dihapus');
                    });
            },
            resetPassword(id){
                this.$Progress.start();
                this.$toasted.show('Mereset kata sandi ...');
                const data = {
                    user_id: id
                };
                axios.post(`${RESTURIV1}/users/resetpassword`, data)
                .then(() => {
                    this.$Progress.finish();
                    this.$toasted.success('Kata sandi berhasil direset');
                })
                .catch(err => {
                    this.$Progress.finish();
                    this.$toasted.danger('Kata sandi gagal direset');
                });
            },
            resetForm() {
                this.form.reset();
                this.form.clear();
            }
        },
        mounted() {
            const data = {
                title: "Pengguna"
            }
            Fire.$emit('PageChange', data);
        },
        created() {
            this.getUsers();
            Fire.$on('DataUpdated', () => {
                this.getUsers();
            });
        }
    }

</script>
