import { createStore } from "vuex"
import auth from "./modules/auth"
import book from "./modules/book"
import googleDriveFiles from "./modules/google-drive-files";
import transaction from "./modules/transaction"
import alert from "./modules/alert"
import daily_sale from "./modules/daily-sale"
import ui from "./modules/ui"

export default createStore({
    modules: {
        auth,
        book,
        googleDriveFiles,
        transaction,
        alert,
        daily_sale,
        ui
    }
})
