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

            <button type="submit" class="btn btn-default" @click="addReply">
                提交
            </button>
        </div>


        <p class="text-center" v-else>请先<a href="/login">登录</a>，然后再发表回复 </p>

    </div>
</template>

<script>
    export default {
        name: "new-reply",

        props: ['endpoint'],

        data() {
            return {
                body:'',
            }
        },

        computed:{
            signIn() {
                return window.App.signIn;
            }
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body})
                     .then(({data}) => {
                           this.body = '';

                           flash('回复已发送');

                           this.$emit('created',data)
                     });
            }
        }
    }
</script>

<style scoped>

</style>