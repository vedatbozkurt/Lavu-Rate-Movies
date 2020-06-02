/*
* @Author: @vedatbozkurt
* @Date:   2020-04-10 16:28:03
* @Last Modified by:   @vedatbozkurt
* @Last Modified time: 2020-04-10 16:56:37
*/
import Vue from 'vue'
import VueI18n from 'vue-i18n'
//
Vue.use(VueI18n)

// Ready translated locale messages
const messages = {
    en: {
        message: {
            hello: 'hello',
            company: 'Company',
        }
    },
    tr: {
        message: {
            hello: 'merhaba',
            company: 'Firma',
        }
    },
    fr: {
        message: {
            hello: 'Bonjour',
            company: 'Firma',
        }
    }
}
 // localStorage.removeItem('userlocale')
// localStorage.setItem('userlocale', 'fr');

var locale = localStorage.getItem('userlocale')
if (!locale) {
    locale = 'en';
}
const i18n = new VueI18n({
locale: locale, // set locale
messages, // set locale messages
})
export default i18n
