<script>
    import Replies from '../components/Replies.vue'

    export default {
        name: 'thread',

        props: ['initialRepliesCount'],

        components: { Replies },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }
    }
</script>