<template>
    <div>
        <facebook-loading v-show="isLoading"></facebook-loading>
        <div class="row" v-show="!isLoading">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Data Informasi Website</h3>
                        <small>Semua data informasi website yang tersimpan di sistem</small>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="update" @keydown="form.Keydown($event)">
                            <div class="form-group">
                                <label>Nama website</label>
                                <input type="text" class="form-control" v-model="form.web_name"
                                    :class="{'is-invalid': form.errors.has('web_name')}">
                                <has-error :form="form" field="web_name"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea cols="30" rows="3" class="form-control" v-model="form.address"
                                          :class="{'is-invalid': form.errors.has('address')}"></textarea>
                                <has-error :form="form" field="address"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" v-model="form.email"
                                       :class="{'is-invalid': form.errors.has('email')}">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <label>No. Hp/Telpon</label>
                                <input type="text" class="form-control" v-model="form.phone_number"
                                       :class="{'is-invalid': form.errors.has('phone_number')}">
                                <has-error :form="form" field="phone_number"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="text" class="form-control" v-model="form.facebook"
                                       :class="{'is-invalid': form.errors.has('facebook')}">
                                <has-error :form="form" field="facebook"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="text" class="form-control" v-model="form.instagram"
                                       :class="{'is-invalid': form.errors.has('instagram')}">
                                <has-error :form="form" field="instagram"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Youtube</label>
                                <input type="text" class="form-control" v-model="form.youtube"
                                       :class="{'is-invalid': form.errors.has('youtube')}">
                                <has-error :form="form" field="youtube"></has-error>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" v-if="$can('pengaturan.ubah')">
                                    <i class="align-middle c-icon cil-save mr-1"></i>
                                    Ubah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Ubah logo</h3>
                        <small>Ubah logo dari sistem atau website</small>
                    </div>
                    <div class="card-body">
                        <form action="javascript:void(0);" @submit.prevent="submitImage">
                            <div class="form-group">
                                <label>Logo baru</label>
                                <input type="file" name="image" ref="imageFile" @change="imageUpload($event.target)" class="form-control" required>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                     :style="{width: progressBar + '%'}"
                                     :aria-valuenow="progressBar"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                            <button :disabled="progressLoading" type="submit" class="btn btn-primary mt-2">
                                <i class="align-middle c-icon cil-image mr-1"></i>
                                Ubah
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Logo saat ini</h3>
                        <small>Logo saat ini yang digunakan disistem atau website</small>
                    </div>
                    <div class="card-body">
                        <img :src="logo" alt="logo">
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
                    web_name: '',
                    address: '',
                    email: '',
                    phone_number: '',
                    facebook: '',
                    instagram: '',
                    youtube: ''
                }),
                logo: '',
                isLoading: false,
                assetUri: '/storage/',
                progressBar: 0,
                image: '',
                progressLoading: false,
            }
        },
        methods: {
            getData() {
                this.isLoading = true;
                this.$Progress.start();
                axios.get(`${RESTURIV1}/setting`)
                    .then(res => {
                        this.form.fill(res.data.setting);
                        this.logo = this.assetUri + res.data.setting.logo;
                        this.$Progress.finish();
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error(err);
                    });
            },
            update(){
                this.$Progress.start();
                this.form.post(`${RESTURIV1}/setting`)
                    .then(() => {
                        this.$Progress.finish();
                        Fire.$emit('DataUpdated');
                        this.$toasted.success('Data berhasil disimpan');
                    })
                    .catch(err => {
                        this.$Progress.finish();
                        this.$toasted.error('Data gagal disimpan');
                    });
            },
            imageUpload(event) {
                this.image = event.files[0]
            },
            //MENGIRIM FILE UNTUK DI-UPLOAD
            submitImage() {
                this.$Progress.start();
                this.progressLoading = true
                let formData = new FormData();
                formData.append('image', this.image);

                axios.post(`${RESTURIV1}/setting/updatelogo`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    //FUNGSI INI YANG MEMILIKI PERAN UNTUK MENGUBAH SEBERAPA JAUH PROGRESS UPLOAD FILE BERJALAN
                    onUploadProgress: function( progressEvent ) {
                        //DATA TERSEBUT AKAN DI ASSIGN KE VARIABLE progressBar
                        this.progressBar = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
                    }.bind(this)
                })
                    .then(() => {
                        this.$Progress.finish();
                        setTimeout(() => {
                            this.progressLoading = false;
                            this.reset();
                        });
                        Fire.$emit('LogoChage');
                        this.$toasted.success('Logo berhasil diperbarui');
                        this.image = '';
                    }).catch(() => {
                        this.$Progress.finish();
                        this.$toasted.error('Logo gagal diperbarui');
                    });
            },
            //RESET FORM UPLOAD
            reset() {
                this.$refs.imageFile.value = null;
            },

        },
        mounted() {
            const data = {
                title: "Pengaturan"
            }
            Fire.$emit('PageChange', data);
        },
        created() {
            this.getData();
            Fire.$on('DataUpdated', () => {
                this.getData();
            });
        }
    }

</script>
