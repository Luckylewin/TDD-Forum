<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body"
                          id="body"
                          class="form-control"
                          placeholder="说点什么吧..."
                          rows="5"
                          required
                          v-model="body"></textarea>
            </div>

            <button type="submit" class="btn btn-default" :disabled="isDisabled" @click="addReply">
                提交
            </button>
        </div>


        <p class="text-center" v-else>请先<a href="/login">登录</a>，然后再发表回复 </p>

    </div>
</template>

<script>
    import 'jquery.caret'
    import 'at.js'

    export default {
        name: "new-reply",

        data() {
            return {
                body:'',
                isDisabled: false,
            }
        },

        mounted() {
            $('#body').atwho({
               at:"@",
               delay:750,
               callbacks: {
                   remoteFilter: function (query,callback) {
                       $.getJSON("/api/users",{name:query},function(usernames) {
                            callback(usernames);
                       });
                   }
               }
            });
        },

        methods: {

            endpoint() {
                return location.pathname + '/replies';
            },

            addReply() {
                this.isDisabled = true;
                axios.post(this.endpoint(), { body: this.body})
                    .catch(error => {
                        flash(':( ' + error.response.data, 'danger');
                     })
                     .then((res) => {
                           if (typeof res === 'object') {
                               this.body = '';

                               flash('回复已发送');

                               this.$emit('created',res.data);

                               this.isDisabled = false;
                           }
                     });
            }
        }
    }
</script>

<style scoped>

</style>