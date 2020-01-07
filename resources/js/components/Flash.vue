<template>
    <div class="alert alert-flash fade show"
         :class="'alert-'+level"
         role="alert"
         v-show="show"
         v-text="body">
<!--        <strong>SUCCESS!</strong> {{ body }}-->
<!--            <span aria-hidden="true">&times;</span>-->
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },

        created() {
            if (this.message) {
                this.flash();
            }

            window.events.$on('flash', data => this.flash(data));
        },

        methods: {

            flash(data){
                if(data){
                    this.body = data.message;
                    this.data = data.level;
                }
                this.show = true;

                this.hide();
            },

            hide(){
                setTimeout(() => {
                    this.show = false;
                }, 3000 );
            }
        }
    }
</script>

<style scoped>
    .alert-flash{
        position: fixed;
        right: 25px;
        bottom: 80px;
    }
</style>
