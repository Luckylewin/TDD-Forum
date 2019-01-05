<template>
    <div>
        <div v-if="signIn">
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
    export default {
        name: "new-reply",

        data() {
            return {
                body:'',
                isDisabled: false,
            }
        },

        computed:{
            signIn() {
                return window.App.signIn;
            }
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
                     .then(({data}) => {
                           this.body = '';

                           flash('回复已发送');

                           this.$emit('created',data);

                           this.isDisabled = false;
                     });
            }
        }
    }
</script>

<style scoped>

</style>