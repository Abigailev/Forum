<template>
     <div>
         <div v-for="(reply, index) in items" :key="reply.id">
              <reply :reply="reply" @deleted="remove(index)"></reply>
         </div>

         <paginator :dataSet="dataSet" @change="fetch"></paginator>

         <p v-if="$parent.locked">
             This thread hass been locked. No more replies:[
         </p>

         <new-reply  @created="add" v-else></new-reply>

     </div>
</template>

<script>
    import Reply from "./Reply.vue";
    import NewReply from "./NewReply.vue";
    import Collection from "../mixins/Collection.js";

    export default {

       components: { Reply, NewReply },
       mixins: [collection],

        data(){
           return {dataSet: false};
        },

        created(){
           this.fetch();
        },

        methods: {

           fetch(page){
               axios.get(this.url(page)).then(this.refresh);
           },
            url(page){

               if(! page){
                   let query = location.search.match(/page=(\d+)/);
                   page = query ? query[1] : 1;
               }

                return `${location.pathname}/replies?page=` + page;
            },

           refresh({data}){
                 this.datSet = data;
                 this.items = data.data;

                 window.scrollTo(0,0);
           }
        }
    }
</script>

<style scoped>

</style>
