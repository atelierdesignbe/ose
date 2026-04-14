const KEY_NUMBERS_SELECTOR = '.key-numbers-number[data-key-numbers-value]'
const KEY_NUMBERS_WRAPPER_SELECTOR = '.key-numbers-wrapper[data-key-numbers-observe]'
const KEY_NUMBERS_FIT_SELECTOR = '.key-numbers-number-row[data-key-numbers-fit="true"]'
const DIGIT_PATTERN = /\d/
const MAX_STAGGERED_DIGITS = 13
const CONFIG = {
  baseJumps: 12,
  jumpStagger: 5,
  baseDuration: 1.9,
  durationStagger: 0.22,
}

let keyNumbersResizeObserver = null
let keyNumbersAnimationFrame = null
let keyNumbersFitAnimationFrame = null
const keyNumbersClassObservers = new WeakMap()
const keyNumbersParentClassObservers = new WeakMap()
const keyNumbersAnimationTimeouts = new WeakMap()
let keyNumbersInitialLoadReady = document.readyState === 'complete'

function getKeyNumberElements(root = document) {
  if (!root) {
    return []
  }

  if (root.matches?.(KEY_NUMBERS_SELECTOR)) {
    return [root]
  }

  if (!root.querySelectorAll) {
    return []
  }

  return Array.from(root.querySelectorAll(KEY_NUMBERS_SELECTOR))
}

function getKeyNumberWrappers(root = document) {
  if (!root) {
    return []
  }

  if (root.matches?.(KEY_NUMBERS_WRAPPER_SELECTOR)) {
    return [root]
  }

  if (!root.querySelectorAll) {
    return []
  }

  return Array.from(root.querySelectorAll(KEY_NUMBERS_WRAPPER_SELECTOR))
}

function getKeyNumberFitRows(root = document) {
  if (!root) {
    return []
  }

  if (root.matches?.(KEY_NUMBERS_FIT_SELECTOR)) {
    return [root]
  }

  if (!root.querySelectorAll) {
    return []
  }

  return Array.from(root.querySelectorAll(KEY_NUMBERS_FIT_SELECTOR))
}

function getKeyNumberValue(element) {
  const rawValue = element.dataset.keyNumbersValue ?? element.textContent ?? ''
  return String(rawValue)
}

function updateKeyNumberInlineSpacing(element) {
  const styles = window.getComputedStyle(element)
  const letterSpacing = styles.letterSpacing
  const fontSize = parseFloat(styles.fontSize)
  const letterSpacingPx = parseFloat(letterSpacing)

  if (
    !Number.isFinite(letterSpacingPx) ||
    !Number.isFinite(fontSize) ||
    fontSize <= 0 ||
    Math.abs(letterSpacingPx) < 0.1
  ) {
    element.style.removeProperty('--key-numbers-inline-letter-spacing')
    return
  }

  element.style.setProperty('--key-numbers-inline-letter-spacing', `${letterSpacingPx / fontSize}em`)
}

function usesAOSTrigger(wrapper) {
  return wrapper?.classList.contains('aos') || wrapper?.classList.contains('animates-on-scroll')
}

function isAOSSuppressedByParent(wrapper) {
  return wrapper?.parentElement?.closest('.aos-disable-children') != null
}

function getAOSTriggerParent(wrapper) {
  const disablingParent = wrapper?.parentElement?.closest('.aos-disable-children')
  if (!disablingParent) {
    return null
  }

  return disablingParent.closest('.aos, .animates-on-scroll') ?? disablingParent
}

function isWrapperAnimated(wrapper) {
  if (wrapper.classList.contains('animated')) {
    return true
  }

  if (isAOSSuppressedByParent(wrapper)) {
    const triggerParent = getAOSTriggerParent(wrapper)
    return triggerParent?.classList.contains('animated') ?? false
  }

  return false
}

function setupKeyNumber(element) {
  if (element.dataset.keyNumbersInitialized === 'true') {
    return
  }

  const value = getKeyNumberValue(element)

  element.dataset.keyNumbersValue = value
  element.dataset.keyNumbersInitialized = 'true'
  element.setAttribute('role', 'text')
  element.setAttribute('aria-label', value)
  updateKeyNumberInlineSpacing(element)

  resetKeyNumberElement(element)
}

function resetKeyNumberElement(element) {
  element.dataset.keyNumbersEnhanced = 'false'
  renderStaticKeyNumberElement(element)

  updateFitRow(element.closest(KEY_NUMBERS_FIT_SELECTOR))
}

function createStaticChar(char) {
  const staticChar = document.createElement('span')
  staticChar.className = 'key-numbers-static-char'
  staticChar.setAttribute('aria-hidden', 'true')
  staticChar.textContent = char
  return staticChar
}

function createSpaceSegment(value) {
  const spaceSegment = document.createElement('span')
  spaceSegment.className = 'key-numbers-space'
  spaceSegment.setAttribute('aria-hidden', 'true')
  spaceSegment.textContent = value
  return spaceSegment
}

function createDigitWindow(char, activeDigitCount, { animated = false } = {}) {
  let stripText = char
  let animationStyle = null

  if (animated) {
    const staggerIndex = Math.min(activeDigitCount, MAX_STAGGERED_DIGITS - 1)
    const jumps = CONFIG.baseJumps + staggerIndex * CONFIG.jumpStagger
    const duration = CONFIG.baseDuration + staggerIndex * CONFIG.durationStagger

    stripText = ''
    for (let index = 0; index < jumps; index++) {
      stripText += Math.floor(Math.random() * 10) + '\n'
    }
    stripText += char

    animationStyle = {
      duration: `${duration}s`,
      transform: `translateY(calc(var(--key-numbers-spacing, 1em) * -${jumps} + var(--key-numbers-font-nudge, 0em)))`,
    }
  }

  const digitWindow = document.createElement('span')
  digitWindow.className = 'key-numbers-digit-window'
  digitWindow.setAttribute('aria-hidden', 'true')

  const digitStrip = document.createElement('span')
  digitStrip.className = 'key-numbers-digit-strip'
  digitStrip.textContent = stripText
  digitWindow.appendChild(digitStrip)

  return {
    digitWindow,
    animationStyle,
  }
}

function buildKeyNumberContent(value, { animated = false } = {}) {
  const fragment = document.createDocumentFragment()
  const animationStyles = []
  let activeDigitCount = 0
  let hasDigits = false

  ;(value.match(/\s+|\S+/gu) ?? []).forEach((segmentValue) => {
    if (/^\s+$/u.test(segmentValue)) {
      fragment.appendChild(createSpaceSegment(segmentValue))
      return
    }

    const word = document.createElement('span')
    word.className = 'key-numbers-word'
    word.setAttribute('aria-hidden', 'true')

    Array.from(segmentValue).forEach((char) => {
      if (DIGIT_PATTERN.test(char)) {
        const { digitWindow, animationStyle } = createDigitWindow(char, activeDigitCount, {
          animated,
        })

        word.appendChild(digitWindow)
        if (animationStyle) {
          animationStyles.push(animationStyle)
        }
        activeDigitCount += 1
        hasDigits = true
        return
      }

      word.appendChild(createStaticChar(char))
    })

    fragment.appendChild(word)
  })

  return { fragment, animationStyles, hasDigits }
}

function renderStaticKeyNumberElement(element) {
  const value = getKeyNumberValue(element)
  const { fragment, hasDigits } = buildKeyNumberContent(value)

  if (hasDigits) {
    element.replaceChildren(fragment)
    return
  }

  element.replaceChildren(document.createTextNode(value))
}

function animateKeyNumberElement(element) {
  setupKeyNumber(element)
  updateKeyNumberInlineSpacing(element)

  const value = getKeyNumberValue(element)
  if (!DIGIT_PATTERN.test(value)) {
    resetKeyNumberElement(element)
    return
  }

  const { fragment, animationStyles, hasDigits } = buildKeyNumberContent(value, { animated: true })
  if (!hasDigits) {
    resetKeyNumberElement(element)
    return
  }

  element.dataset.keyNumbersEnhanced = 'true'
  element.replaceChildren(fragment)
  void element.offsetWidth

  element.querySelectorAll('.key-numbers-digit-strip').forEach((strip, index) => {
    const animationStyle = animationStyles[index]
    if (!animationStyle) {
      return
    }

    strip.style.transitionDuration = animationStyle.duration
    strip.style.transform = animationStyle.transform
  })

  updateFitRow(element.closest(KEY_NUMBERS_FIT_SELECTOR))
}

function getFitWrapper(row) {
  if (!row) {
    return null
  }

  return row.closest('.key-numbers') ?? row.parentElement ?? row.closest(KEY_NUMBERS_WRAPPER_SELECTOR)
}

function ensureResizeObserver() {
  if (keyNumbersResizeObserver || typeof ResizeObserver === 'undefined') {
    return keyNumbersResizeObserver
  }

  keyNumbersResizeObserver = new ResizeObserver((entries) => {
    entries.forEach((entry) => {
      updateKeyNumberFitIn(entry.target)
    })
  })

  return keyNumbersResizeObserver
}

function observeKeyNumberWrapper(wrapper) {
  if (!wrapper || keyNumbersClassObservers.has(wrapper) || typeof MutationObserver === 'undefined') {
    return
  }

  const syncCallback = () => {
    syncKeyNumberWrapper(wrapper)
  }

  const observer = new MutationObserver(syncCallback)

  observer.observe(wrapper, {
    attributes: true,
    attributeFilter: ['class'],
  })

  // When AOS is suppressed by a parent's aos-disable-children,
  // the 'animated' class is added to the parent, not the wrapper.
  // Observe the parent so we detect when it becomes animated.
  if (isAOSSuppressedByParent(wrapper)) {
    const triggerParent = getAOSTriggerParent(wrapper)
    if (triggerParent && !keyNumbersParentClassObservers.has(wrapper)) {
      const parentObserver = new MutationObserver(syncCallback)
      parentObserver.observe(triggerParent, {
        attributes: true,
        attributeFilter: ['class'],
      })
      keyNumbersParentClassObservers.set(wrapper, parentObserver)
    }
  }

  keyNumbersClassObservers.set(wrapper, observer)
}

function clearScheduledKeyNumberAnimation(wrapper) {
  const timeoutId = keyNumbersAnimationTimeouts.get(wrapper)
  if (timeoutId === undefined) {
    return
  }

  window.clearTimeout(timeoutId)
  keyNumbersAnimationTimeouts.delete(wrapper)
}

function completeKeyNumberAnimation(wrapper) {
  const hasAOSTrigger = usesAOSTrigger(wrapper)
  const shouldAnimate = hasAOSTrigger ? isWrapperAnimated(wrapper) : true

  if (!shouldAnimate) {
    wrapper.dataset.keyNumbersAnimationState = 'reset'
    resetKeyNumbersIn(wrapper)
    return
  }

  wrapper.dataset.keyNumbersAnimationState = 'animated'
  animateKeyNumbersIn(wrapper)
}

function scheduleWrapperAnimation(wrapper, waitMs = 0) {
  wrapper.dataset.keyNumbersAnimationState = 'scheduled'

  const timeoutId = window.setTimeout(() => {
    keyNumbersAnimationTimeouts.delete(wrapper)

    window.requestAnimationFrame(() => {
      window.requestAnimationFrame(() => {
        completeKeyNumberAnimation(wrapper)
      })
    })
  }, Math.max(waitMs, 0))

  keyNumbersAnimationTimeouts.set(wrapper, timeoutId)
}

function getWrapperAnimationWaitMs(wrapper) {
  if (!wrapper) {
    return 0
  }

  // When AOS is suppressed, check the parent's animation timing instead
  const animationTarget = isAOSSuppressedByParent(wrapper)
    ? (getAOSTriggerParent(wrapper) ?? wrapper)
    : wrapper

  if (typeof animationTarget.getAnimations === 'function') {
    const animations = animationTarget.getAnimations().filter((animation) => {
        const target = animation.effect?.target
        return target === animationTarget
      })
    const runningAnimationWait = animations
      .filter((animation) => ['pending', 'running'].includes(animation.playState))
      .reduce((maxWait, animation) => {
        const endTime = animation.effect?.getComputedTiming?.().endTime
        const currentTime = animation.currentTime ?? 0

        if (!Number.isFinite(endTime) || !Number.isFinite(currentTime)) {
          return maxWait
        }

        const startTime = getAOSCountStartTimeMs(endTime)
        return Math.max(maxWait, Math.max(startTime - currentTime, 0))
      }, 0)

    if (runningAnimationWait > 0) {
      return runningAnimationWait
    }

    if (animations.length > 0) {
      return 0
    }
  }

  const styles = window.getComputedStyle(animationTarget)
  const names = styles.animationName.split(',').map((value) => value.trim())
  const durations = styles.animationDuration.split(',').map((value) => value.trim())
  const delays = styles.animationDelay.split(',').map((value) => value.trim())

  return names.reduce((maxWait, name, index) => {
    if (!name || name === 'none') {
      return maxWait
    }

    const duration = parseAnimationTimeToMs(durations[index] ?? durations[0] ?? '0s')
    const delay = parseAnimationTimeToMs(delays[index] ?? delays[0] ?? '0s')

    return Math.max(maxWait, delay + getAOSCountStartTimeMs(duration))
  }, 0)
}

function parseAnimationTimeToMs(value) {
  if (!value) {
    return 0
  }

  const parsed = parseFloat(value)
  if (!Number.isFinite(parsed)) {
    return 0
  }

  return value.trim().endsWith('ms') ? parsed : parsed * 1000
}

function getAOSCountStartTimeMs(totalMs) {
  return 0
}

function updateFitRow(row) {
  if (!row) {
    return
  }

  row.style.removeProperty('font-size')

  const wrapper = getFitWrapper(row)
  const availableWidth = wrapper?.clientWidth ?? 0
  const baseFontSize = parseFloat(window.getComputedStyle(row).fontSize)

  if (!availableWidth || !Number.isFinite(baseFontSize) || baseFontSize <= 0) {
    return
  }

  let nextFontSize = baseFontSize
  for (let attempt = 0; attempt < 10; attempt += 1) {
    const currentWidth = row.scrollWidth
    if (!currentWidth || currentWidth <= availableWidth + 0.5) {
      break
    }

    nextFontSize *= availableWidth / currentWidth
    row.style.fontSize = `${nextFontSize}px`
  }
}

function scheduleKeyNumberFitUpdate(root = document) {
  if (keyNumbersFitAnimationFrame !== null) {
    window.cancelAnimationFrame(keyNumbersFitAnimationFrame)
  }

  keyNumbersFitAnimationFrame = window.requestAnimationFrame(() => {
    keyNumbersFitAnimationFrame = null
    updateKeyNumberFitIn(root)
  })
}

function syncKeyNumberWrapper(wrapper) {
  if (!wrapper) {
    return
  }

  const hasAOSTrigger = usesAOSTrigger(wrapper)
  const shouldAnimate = hasAOSTrigger ? isWrapperAnimated(wrapper) : true
  const currentState = wrapper.dataset.keyNumbersAnimationState ?? 'reset'

  if (!shouldAnimate) {
    if (currentState === 'reset') {
      return
    }

    clearScheduledKeyNumberAnimation(wrapper)
    wrapper.dataset.keyNumbersAnimationState = 'reset'
    resetKeyNumbersIn(wrapper)
    return
  }

  if (currentState === 'scheduled' || currentState === 'animated') {
    return
  }

  const waitMs = hasAOSTrigger ? getWrapperAnimationWaitMs(wrapper) : 0
  clearScheduledKeyNumberAnimation(wrapper)

  scheduleWrapperAnimation(wrapper, waitMs <= 16 ? 0 : waitMs)
}

function scheduleKeyNumberAnimationSync(root = document) {
  if (keyNumbersAnimationFrame !== null) {
    window.cancelAnimationFrame(keyNumbersAnimationFrame)
  }

  keyNumbersAnimationFrame = window.requestAnimationFrame(() => {
    keyNumbersAnimationFrame = null
    syncKeyNumbersIn(root)
  })
}

export function initKeyNumbersIn(root = document, { animate = true } = {}) {
  getKeyNumberElements(root).forEach((element) => {
    setupKeyNumber(element)
  })

  getKeyNumberWrappers(root).forEach((wrapper) => {
    observeKeyNumberWrapper(wrapper)
  })

  getKeyNumberFitRows(root).forEach((row) => {
    const wrapper = getFitWrapper(row)
    if (!wrapper || wrapper.dataset.keyNumbersFitObserved === 'true') {
      return
    }

    wrapper.dataset.keyNumbersFitObserved = 'true'
    ensureResizeObserver()?.observe(wrapper)
  })

  scheduleKeyNumberFitUpdate(root)
  if (animate) {
    scheduleKeyNumberAnimationSync(root)
  }
}

export function animateKeyNumbersIn(root) {
  getKeyNumberElements(root).forEach((element) => {
    animateKeyNumberElement(element)
  })
}

export function resetKeyNumbersIn(root) {
  getKeyNumberElements(root).forEach((element) => {
    setupKeyNumber(element)
    resetKeyNumberElement(element)
  })
}

export function updateKeyNumberFitIn(root = document) {
  getKeyNumberElements(root).forEach((element) => {
    updateKeyNumberInlineSpacing(element)
  })

  getKeyNumberFitRows(root).forEach((row) => {
    updateFitRow(row)
  })
}

export function syncKeyNumbersIn(root = document) {
  getKeyNumberWrappers(root).forEach((wrapper) => {
    syncKeyNumberWrapper(wrapper)
  })
}

if (document.fonts?.ready) {
  document.fonts.ready.then(() => {
    scheduleKeyNumberFitUpdate(document)
    if (keyNumbersInitialLoadReady) {
      scheduleKeyNumberAnimationSync(document)
    }
  })
}

const keyNumbersAnimationSyncEvents =
  typeof MutationObserver === 'undefined'
    ? ['scroll', 'resize', 'orientationchange', 'touchmove']
    : ['resize', 'orientationchange']

;['DOMContentLoaded', 'htmx:afterSwap', 'htmx:afterSettle'].forEach((event) => {
  document.addEventListener(event, () => {
    initKeyNumbersIn(document, { animate: keyNumbersInitialLoadReady })
  })
})

;['load'].forEach((event) => {
  window.addEventListener(event, () => {
    keyNumbersInitialLoadReady = true
    initKeyNumbersIn(document)
  })
})

;keyNumbersAnimationSyncEvents.forEach((event) => {
  window.addEventListener(event, () => {
    scheduleKeyNumberAnimationSync(document)
    scheduleKeyNumberFitUpdate(document)
  })
})

window.visualViewport?.addEventListener('resize', () => {
  scheduleKeyNumberFitUpdate(document)
})
