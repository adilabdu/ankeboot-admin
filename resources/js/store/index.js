import { createStore } from "vuex"
import auth from "./modules/auth"
import book from "./modules/book"
import transaction from "./modules/transaction"

export default createStore({
    modules: {
        auth,
        book,
        transaction
    }
})
