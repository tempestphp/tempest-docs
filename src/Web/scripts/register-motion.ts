import { animate } from 'motion/mini'
import { inView } from 'motion'

inView('.enter-fade', (element) => {
	animate(element, { opacity: 1, transform: 'translateX(0px)' }, { delay: 0.25 })
}, { amount: 'all' })
