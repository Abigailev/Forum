<template>
    <button class="btn btn-default">Subscribe</button>
</template>

<script>
    export default {
          methods: {
              subscribe(){
                  axios.post(location.pathname + 'subscriptions'):


                  flash('Subscribed');
              }
          }
    }
</script>

<style scoped>

</style>

