<template>

        <div :id="'reply-'+ id" class="card" :class="isBest ? 'card-success':'card'">
            <div class="card-header">
                <div class="level">
                    <h5 class="flex">
                        <a href="'/profiles/'+data.owner.name" v-text="+data.owner.name"></a>
                        said {{ data.created_at }}...
                    </h5>


                    <div v-if="signedIn">
                        <favorite :reply="data"></favorite>
                    </div>


                </div>
            </div>

            <div class="card-body">
                <div v-if="editing">
                    <form @submit="update">
                        <div class="form-group">
                            <textarea name="" id="" class="form-control" v-model="body" required></textarea>
                        </div>

                        <button class="btn btn-xs btn-primary">Update</button>
                        <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                    </form>
                </div>

                <div v-else v-html="body"></div>
            </div>

<!--            @can-->
            <div class="card-footer level">
<!--                <div v-if="canUpdate">-->
                <div v-if="authorize('updateReply', reply)">
                    <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                    <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
                </div>
                    <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-show="! isBest">Best Reply</button>

            </div>
<!--            @endcan-->

        </div>


</template>
<script>
    import Favorite from "./Favorite.vue";
    export default {
        props: ['data'],

        components: {Favorite},
        data(){
            return{
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: this.data.isBest,
                reply: this.data
            };
        },

        // computed: {
        //
        //     // ago(){
        //     //   return momet(this.data.created_at).fromNow() + '...';
        //     // },
        //
        //     // signedIn(){
        //     //     return window.App.signedIn;
        //     // }
        //
        //     // canUpdate(){
        //     //     return this.authorize(user => this.data.user_id == user.id);
        //     //     // return data.user_id == window.App.user.id;
        //     // }
        // },

        created() {
            window.events.$on('best-reply-selected', id=> {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch(
                    '/replies/'+ this.data.id, {
                    body: this.body
                })

                .catch(error => {
                    flash(error.response.data, 'danger');
                });

                this.editing = false;

                flash('Updated');
            },

            destroy(){
                axios.delete('/replies/'+ this.data.id);

                this.$emit('deleted',  this.data.id);
                // $(this.$el). fadeOut(300, () => {
                //     flash('Your reply has been deleted.');
                // });
            },

            markBestReply(){
                //this.isBest = true;

                axios.post('/replies/'+this.data.id+'/best');

                window.events.$emit('best-reply-selected', this.data.id);
            }
        }

    }
</script>



