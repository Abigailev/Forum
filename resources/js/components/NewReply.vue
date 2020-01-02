<template>
    <div>
<!--        @if (auth()->check())-->

<!--        <form method="POST" action="{{$threads->path(). 'replies'}}">-->

      <div v-if="signedIn">
          <div class="form-group">
                <textarea name="body"
                          id="body"
                          class="form-control"
                          placeholder="Wanna leave a comment?"
                          rows="4"
                          required
                          v-model="body"></textarea>
          </div>

          <button type="submit"
                  class="btn btn-default"
                  @click="addReply">Post</button>
      </div>
<!--        </form>-->

        <p class="text-center" v-else>Please <a href="/login">sign in </a> to participate on the disscusion</p>

    </div>
</template>

<script>
    export default {

        data() {
            return {
                body: '',
                endpoint: '/threads/aut/53/replies'
            };
        },

        computed(){
            signedIn(){
                return window.App.signedIn;
            }
        },

        methods: {
            addReply(){
                axios.post(location.pathname + '/replies', {body: this.body })

                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })

                    .then( ({data}) => {
                    this.body = '';

                    flash('Your reply has been posted.');

                    this.$emit('created', data);
                });
            }
        }
    }
</script>

