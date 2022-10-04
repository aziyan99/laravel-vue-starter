<template>
<div>
    <div class="card">
        <div class="card-header">
            <h3>Permission</h3>
            <small>Semua data permission yang tersimpan didalam sistem</small>
        </div>
        <div class="card-body">
            <div class="text-right">
                <button type="button" @click="showBulkDestroyPermissionModal" class="btn btn-danger" v-if="deletePermissions.length > 0 && $can('permission.hapus')">
                    <b>Hapus yang dipilih</b>
                </button>
                <button type="button" disabled class="btn btn-danger" v-if="deletePermissions.length < 1 && $can('permission.hapus')">
                    <b>Hapus yang dipilih</b>
                </button>
                <button class="btn btn-primary" @click="showCreatePermissionModal" v-if="$can('permission.tambah')">
                    <b>Tambah Permission</b>
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
                            <th v-if="$can('permission.hapus')">
                                <input type="checkbox" v-model="allSelect" @click="selectAll" />
                            </th>
                            <th>Nama</th>
                            <th>Guard name</th>
                            <th>Tanggal ditambahkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="permission in permissions.data" :key="permission.id">
                            <td v-if="$can('permission.hapus')"><input type="checkbox" v-model="deletePermissions" :value="`${permission.id}`" /></td>
                            <td>{{ permission.name }}</td>
                            <td>{{ permission.guard_name }}</td>
                            <td>{{ permission.created_at | formatDate }}</td>
                            <td>
                                <button @click="showEditPermissionModal(permission)"  v-if="$can('permission.ubah')" class="btn btn-default btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="showDestroyPermissionModal(permission)" class="btn btn-default btn-sm" v-if="$can('permission.hapus')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination align="right" :data="permissions" @pagination-change-page="getPermissions">
                <span slot="prev-nav">Prev</span>
                <span slot="next-nav">Next</span>
            </pagination>
        </div>
        <!-- Role form -->
        <div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editMode">Tambah Permission Baru</h5>
                        <h5 class="modal-title" v-show="editMode">Ubah Permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updatePermission() : storePermission()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama permission</label>
                                <input type="text" class="form-control" v-model="form.name"
                                    :class="{'is-invalid': form.errors.has('name')}">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Guard name</label>
                                <input type="text" class="form-control" v-model="form.guard_name"
                                    :class="{'is-invalid': form.errors.has('guard_name')}" readonly>
                                <has-error :form="form" field="guard_name"></has-error>
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
        <!-- Delete Role form -->
        <div class="modal fade" id="destroyPermissionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!bulkDeleteMode">Hapus Permission</h5>
                        <h5 class="modal-title" v-show="bulkDeleteMode">Hapus Masal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            Hapus Permission ini?. Pengguna dengan permission ini mungkin tidak akan memiliki akses lagi keresource.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <b>Batal</b>
                        </button>
                        <button type="button" class="btn btn-danger" @click="destroyPermission" v-show="!bulkDeleteMode">
                            <b>Hapus</b>
                        </button>
                        <button type="button" class="btn btn-danger" @click="bulkDestroy" v-show="bulkDeleteMode">
                            <b>Hapus</b>
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
                    guard_name: 'web'
                }),
                editMode: false,
                permissions: {},
                keywords: null,
                count: 5,
                deletePermissions: [],
                allSelect: false,
                bulkDeleteMode: false,
                isLoading: false,
            }
        },
        watch: {
            keywords(after, before) {
                this.getPermissions();
            },
            count(after, before) {
                this.getPermissions();
            }
        },
        methods: {
            getPermissions(page = 1) {
                this.isLoading = true;
                this.$Progress.start();
                axios.get(`${RESTURIV1}/permissions?page=${page}`, {
                        params: {
                            keywords: this.keywords,
                            count: this.count
                        }
                    })
                    .then(res => {
                        this.isLoading = false;
                        this.$Progress.finish();
                        this.permissions = res.data;
                    }).catch(err => {
                        this.isLoading = true;
                        this.$Progress.finish();
                    });
            },
            showCreatePermissionModal() {
                this.editMode = false;
                this.resetForm();
                $('#permissionModal').modal('show');
            },
            storePermission() {
                this.$Progress.start();
                this.form.post(`${RESTURIV1}/permissions`)
                    .then(() => {
                        this.$Progress.finish();
                        $('#permissionModal').modal('hide');
                        this.$toasted.success('Permission berhasil disimpan');
                        Fire.$emit('DataUpdated');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Permission gagal disimpan');
                    });
            },
            showEditPermissionModal(permission) {
                this.editMode = true;
                this.resetForm();
                this.form.fill(permission);
                $('#permissionModal').modal('show');
            },
            updatePermission() {
                this.$Progress.start();
                this.form.put(`${RESTURIV1}/permissions/${this.form.id}`)
                    .then(() => {
                        this.$Progress.finish();
                        $('#permissionModal').modal('hide');
                        this.$toasted.success('Permission berhasil diubah');
                        Fire.$emit('DataUpdated');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Permission gaga diubah');
                    });
            },
            showDestroyPermissionModal(permission) {
                this.bulkDeleteMode = false;
                $('#destroyPermissionModal').modal('show');
                this.form.fill(permission);
            },
            destroyPermission() {
                this.$Progress.finish();
                axios.delete(`${RESTURIV1}/permissions/${this.form.id}`)
                    .then(() => {
                        this.$Progress.finish();
                        this.resetForm();
                        $('#destroyPermissionModal').modal('hide');
                        this.$toasted.success('Permission berhasil dihapus');
                        Fire.$emit('DataUpdated');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.success('Permission gagal dihapus');
                    });
            },
            selectAll() {
                if (this.allSelect == false) {
                    this.allSelect = true;
                    this.permissions.data.forEach(permission => {
                        this.deletePermissions.push(permission.id);
                    });
                } else {
                    this.allSelect = false;
                    this.deletePermissions = [];
                }
            },
            showBulkDestroyPermissionModal() {
                this.bulkDeleteMode = true;
                $('#destroyPermissionModal').modal('show');
            },
            bulkDestroy() {
                this.$Progress.start();
                const data = {
                    'id': this.deletePermissions
                }
                axios.post(`${RESTURIV1}/permissions/bulkdelete`, data)
                    .then(res => {
                        this.$Progress.finish();
                        Fire.$emit('DataUpdated');
                        $('#destroyPermissionModal').modal('hide');
                        this.$toasted.success('Permission berhasil dihapus');
                        this.deletePermissions = [];
                        this.allSelect = false;
                    }).catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Permission gagal dihapus');
                    });
            },
            resetForm() {
                this.form.reset();
                this.form.clear();
            }
        },
        mounted() {
            const data = {
                title: "Permissions"
            }
            Fire.$emit('PageChange', data);
        },
        created() {
            this.getPermissions();
            Fire.$on('DataUpdated', () => {
                this.getPermissions();
            });
        }
    }
</script>
