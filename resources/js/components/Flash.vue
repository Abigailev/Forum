<template>
    <div class="alert alert-success alert-flash fade show" role="alert" v-show="show">
        <strong>SUCCESS!</strong> {{ body }}
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: '',
                show: false
            }
        },

        created() {
            if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', message => this.flash(message));
        },

        methods: {

            flash(message){
                this.body = message;
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
