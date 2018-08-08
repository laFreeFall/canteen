export const money = {
    data() {
        return {
            currencySymbol: '₴'
        }
    },

    methods: {
        formatMoney (money, withCurrency = false, onlyPositive = false) {
            money = money / 100
            money = onlyPositive ? Math.abs(money) : money
            money = money.toFixed(2)
            return withCurrency ? money + this.currencySymbol : money
        },

        transformInputMoney (money) {
            return parseInt(parseFloat(money) * 100)
        },
    }
}