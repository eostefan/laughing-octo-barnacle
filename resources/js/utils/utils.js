const utils = {

    formatDate(date) {
        const d = new Date(date)
        return d.toLocaleDateString()
    },

    avatarInitials(username) {
        const username_array = username.split(" ");
        let initials_string = username_array.map(user => user[0]).join("")
        return initials_string
    },

    formatPrice(balance, currency) {
      return new Intl.NumberFormat("de-DE", { style: "currency", currency: currency }).format( balance )
    },

    debounce(callback, wait) {
        let timeoutId = null;

        return (...args) => {
          window.clearTimeout(timeoutId);

          timeoutId = window.setTimeout(() => {
            callback.apply(null, args);
          }, wait);
        };
      }

}

export default utils
