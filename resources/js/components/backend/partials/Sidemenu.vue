<template>
<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
        <div class="c-sidebar-brand d-lg-down-none">
            <b>{{ web_name }}</b>
        </div>
        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-item" v-if="$can('dashbor.lihat')">
                <router-link class="c-sidebar-nav-link" :to="{name: 'dashboard'}">
                    <i class="c-sidebar-nav-icon c-icon cil-speedometer"></i>
                    Dashbor
                </router-link>
            </li>
            <li class="c-sidebar-nav-title">Menu Utama</li>
            <li class="c-sidebar-nav-item c-sidebar-nav-dropdown" v-if="$can('role.lihat') || $can('permission.lihat') || $can('assignpermission.lihat')">
                <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="javascript:;">
                    <i class="c-sidebar-nav-icon c-icon cil-shield-alt"></i>
                    Hak Akses & Keamanan
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item" v-if="$can('role.lihat')">
                        <router-link class="c-sidebar-nav-link" :to="{name: 'role'}">
                            <span class="c-sidebar-nav-icon"></span>
                            Role
                        </router-link>
                    </li>
                    <li class="c-sidebar-nav-item" v-if="$can('permission.lihat')">
                        <router-link class="c-sidebar-nav-link" :to="{name: 'permission'}">
                            <span class="c-sidebar-nav-icon"></span>
                            Permissions
                        </router-link>
                    </li>
                    <li class="c-sidebar-nav-item" v-if="$can('assignpermission.lihat')">
                        <router-link class="c-sidebar-nav-link" :to="{name: 'assignpermission'}">
                            <span class="c-sidebar-nav-icon"></span>
                            Assign Permissions
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="c-sidebar-nav-item" v-if="$can('pengguna.lihat')">
                <router-link class="c-sidebar-nav-link" :to="{name: 'user'}">
                    <i class="c-sidebar-nav-icon c-icon cil-user-plus"></i>
                    Pengguna
                </router-link>
            </li>
            <li class="c-sidebar-nav-item" v-if="$can('pengaturan.lihat')">
                <router-link class="c-sidebar-nav-link" :to="{name: 'setting'}">
                    <i class="c-sidebar-nav-icon c-icon cil-settings"></i>
                    Pengaturan
                </router-link>
            </li>
            <li class="c-sidebar-nav-item">
                <router-link class="c-sidebar-nav-link" :to="{name: 'profile'}">
                    <i class="c-sidebar-nav-icon c-icon cil-user"></i>
                    Profil
                </router-link>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    data(){
        return{
            pageTitle:'',
            web_name: ''
        }
    },
    methods:{
        getSettingData(){
            axios.get(`${RESTURIV1}/setting`)
                .then(res => {
                    this.web_name = res.data.setting.web_name;
                })
                .catch();
        }
    },
    created(){
        this.getSettingData();
        Fire.$on('PageChange', (data) => {
            this.pageTitle = data.title;
        });
        Fire.$on('DataUpdated', () => {
            this.getSettingData();
        });
    }
}
</script>
