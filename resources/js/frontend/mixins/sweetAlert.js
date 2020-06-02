/*
* @Author: @vedatbozkurt
* @Date:   2020-04-06 23:57:35
* @Last Modified by:   @vedatbozkurt
* @Last Modified time: 2020-04-07 00:36:28
*/
import Vue from 'vue'

Vue.mixin({
  methods: {
    capitalizeFirstLetter(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    },
    myToast(icon,title) {
      return Toast.fire({
        icon: icon,
        title: title
      })
    },
    afterDeleteAlert(icon,text) {
      return Swal.fire({
        icon: icon,
        timer: 3000,
        timerProgressBar: true,
        text: text,
      })
    },

  }
})
