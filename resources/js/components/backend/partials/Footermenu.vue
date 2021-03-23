<template>
    <footer class="c-footer">
        <div><a href="/">{{ web_name }}</a> © 2021</div>
        <div class="ml-auto">Current page&nbsp;<b>{{ pageTitle }}</b></div>
    </footer>
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
