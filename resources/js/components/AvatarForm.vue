<template>
   <div>
       <h1 v-text="user.name"> </h1>

       <img :src="avatar" width="50" height="50">

       <form v-if="canUpdate" method="POST"  enctype="multipart/form-data">
           <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
           <button type="submit" class="btn btn-primary">Add Avatar</button>
       </form>


<!--       <small> Has been here since {{ $profileUser->created_at->diffForHumans() }}</small>-->
   </div>
</template>


<script>

    import ImageUpload from "./ImageUpload";

    export default {
        props: ['user'],
        components: {ImageUpload},

        data(){
            return{
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate(){
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onLoad(avatar){
                this.avatar = avatar.src;

                this.persist(avatar.file);
            },

            persist(avatar){
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
            }
        }
    }
</script>
