const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')
const { vw } = require('../utils/vw')

const firstResponsiveMode = getFirstKey(tokens.responsiveSizing)
const firstColorMode = getFirstKey(tokens.colorSystem)
const buttonSizing = tokens.responsiveSizing[firstResponsiveMode]?.button ?? {}
const buttonColors = tokens.colorSystem[firstColorMode]?.button ?? {}
const buttonTypographyVariants = new Set(
  Object.keys(tokens.textStyles?.Button ?? {}).map((variant) => toKebabCase(variant)),
)

const hasToken = (obj, key) => Object.prototype.hasOwnProperty.call(obj ?? {}, key)
const applyResponsiveValue = (
  rule,
  property,
  sizeTokens,
  variantClass,
  tokenName,
  breakpoint = 'sm',
) => {
  if (!hasToken(sizeTokens, tokenName)) {
    return
  }

  rule[property] = vw(`var(--${variantClass}-${toKebabCase(tokenName)})`, breakpoint)
}

const buttonVariants = Object.keys(buttonSizing).filter((variant) =>
  Object.prototype.hasOwnProperty.call(buttonColors, variant),
)

const buttonVariantStyles = buttonVariants.reduce((acc, variant) => {
  const sizeTokens = buttonSizing[variant] ?? {}
  const colorTokens = buttonColors[variant] ?? {}
  const normalColorTokens = colorTokens.normal ?? {}
  const hoverColorTokens = colorTokens.hover ?? {}
  const variantKey = toKebabCase(variant)
  const variantClass = `button-${variantKey}`
  // OSE patch: drop `:where()` wrapping. Specificity 0 loses to Tailwind's
  // preflight `button { padding: 0 }` reset, stripping button padding & bg.
  const variantSelector = `.${variantClass}`
  const variantRule = {
    cursor: 'pointer',
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'space-between',
  }

  applyResponsiveValue(variantRule, 'paddingLeft', sizeTokens, variantClass, 'paddingLeft')
  applyResponsiveValue(variantRule, 'paddingRight', sizeTokens, variantClass, 'paddingRight')
  applyResponsiveValue(variantRule, 'paddingTop', sizeTokens, variantClass, 'paddingTop')
  applyResponsiveValue(variantRule, 'paddingBottom', sizeTokens, variantClass, 'paddingBottom')
  if (!hasToken(sizeTokens, 'paddingLeft') && !hasToken(sizeTokens, 'paddingRight')) {
    applyResponsiveValue(variantRule, 'paddingInline', sizeTokens, variantClass, 'paddingX')
  }
  if (!hasToken(sizeTokens, 'paddingTop') && !hasToken(sizeTokens, 'paddingBottom')) {
    applyResponsiveValue(variantRule, 'paddingBlock', sizeTokens, variantClass, 'paddingY')
  }
  if (hasToken(sizeTokens, 'gap')) {
    variantRule.gap = vw(`var(--${variantClass}-gap)`, 'sm')
  }
  if (
    hasToken(sizeTokens, 'borderLeftWidth') ||
    hasToken(sizeTokens, 'borderRightWidth') ||
    hasToken(sizeTokens, 'borderTopWidth') ||
    hasToken(sizeTokens, 'borderBottomWidth') ||
    hasToken(sizeTokens, 'borderWidth')
  ) {
    variantRule.borderStyle = 'solid'
  }
  applyResponsiveValue(
    variantRule,
    'borderLeftWidth',
    sizeTokens,
    variantClass,
    'borderLeftWidth',
  )
  applyResponsiveValue(
    variantRule,
    'borderRightWidth',
    sizeTokens,
    variantClass,
    'borderRightWidth',
  )
  applyResponsiveValue(
    variantRule,
    'borderTopWidth',
    sizeTokens,
    variantClass,
    'borderTopWidth',
  )
  applyResponsiveValue(
    variantRule,
    'borderBottomWidth',
    sizeTokens,
    variantClass,
    'borderBottomWidth',
  )
  if (
    !hasToken(sizeTokens, 'borderLeftWidth') &&
    !hasToken(sizeTokens, 'borderRightWidth') &&
    !hasToken(sizeTokens, 'borderTopWidth') &&
    !hasToken(sizeTokens, 'borderBottomWidth')
  ) {
    applyResponsiveValue(variantRule, 'borderWidth', sizeTokens, variantClass, 'borderWidth')
  }
  applyResponsiveValue(
    variantRule,
    'borderTopLeftRadius',
    sizeTokens,
    variantClass,
    'borderTopLeftRadius',
  )
  applyResponsiveValue(
    variantRule,
    'borderTopRightRadius',
    sizeTokens,
    variantClass,
    'borderTopRightRadius',
  )
  applyResponsiveValue(
    variantRule,
    'borderBottomLeftRadius',
    sizeTokens,
    variantClass,
    'borderBottomLeftRadius',
  )
  applyResponsiveValue(
    variantRule,
    'borderBottomRightRadius',
    sizeTokens,
    variantClass,
    'borderBottomRightRadius',
  )
  if (
    !hasToken(sizeTokens, 'borderTopLeftRadius') &&
    !hasToken(sizeTokens, 'borderTopRightRadius') &&
    !hasToken(sizeTokens, 'borderBottomLeftRadius') &&
    !hasToken(sizeTokens, 'borderBottomRightRadius')
  ) {
    applyResponsiveValue(variantRule, 'borderRadius', sizeTokens, variantClass, 'borderRadius')
  }
  if (
    hasToken(sizeTokens, 'shadowX') &&
    hasToken(sizeTokens, 'shadowY') &&
    hasToken(sizeTokens, 'shadowBlur')
  ) {
    variantRule.boxShadow = `${vw(`var(--${variantClass}-shadow-x)`, 'sm')} ${vw(`var(--${variantClass}-shadow-y)`, 'sm')} ${vw(`var(--${variantClass}-shadow-blur)`, 'sm')} 0px var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`
  }

  const desktopRule = {}
  applyResponsiveValue(desktopRule, 'paddingLeft', sizeTokens, variantClass, 'paddingLeft', 'lg')
  applyResponsiveValue(
    desktopRule,
    'paddingRight',
    sizeTokens,
    variantClass,
    'paddingRight',
    'lg',
  )
  applyResponsiveValue(desktopRule, 'paddingTop', sizeTokens, variantClass, 'paddingTop', 'lg')
  applyResponsiveValue(
    desktopRule,
    'paddingBottom',
    sizeTokens,
    variantClass,
    'paddingBottom',
    'lg',
  )
  if (!hasToken(sizeTokens, 'paddingLeft') && !hasToken(sizeTokens, 'paddingRight')) {
    applyResponsiveValue(desktopRule, 'paddingInline', sizeTokens, variantClass, 'paddingX', 'lg')
  }
  if (!hasToken(sizeTokens, 'paddingTop') && !hasToken(sizeTokens, 'paddingBottom')) {
    applyResponsiveValue(desktopRule, 'paddingBlock', sizeTokens, variantClass, 'paddingY', 'lg')
  }
  if (hasToken(sizeTokens, 'gap')) {
    desktopRule.gap = vw(`var(--${variantClass}-gap)`, 'lg')
  }
  applyResponsiveValue(
    desktopRule,
    'borderLeftWidth',
    sizeTokens,
    variantClass,
    'borderLeftWidth',
    'lg',
  )
  applyResponsiveValue(
    desktopRule,
    'borderRightWidth',
    sizeTokens,
    variantClass,
    'borderRightWidth',
    'lg',
  )
  applyResponsiveValue(
    desktopRule,
    'borderTopWidth',
    sizeTokens,
    variantClass,
    'borderTopWidth',
    'lg',
  )
  applyResponsiveValue(
    desktopRule,
    'borderBottomWidth',
    sizeTokens,
    variantClass,
    'borderBottomWidth',
    'lg',
  )
  if (
    !hasToken(sizeTokens, 'borderLeftWidth') &&
    !hasToken(sizeTokens, 'borderRightWidth') &&
    !hasToken(sizeTokens, 'borderTopWidth') &&
    !hasToken(sizeTokens, 'borderBottomWidth')
  ) {
    applyResponsiveValue(desktopRule, 'borderWidth', sizeTokens, variantClass, 'borderWidth', 'lg')
  }
  applyResponsiveValue(
    desktopRule,
    'borderTopLeftRadius',
    sizeTokens,
    variantClass,
    'borderTopLeftRadius',
    'lg',
  )
  applyResponsiveValue(
    desktopRule,
    'borderTopRightRadius',
    sizeTokens,
    variantClass,
    'borderTopRightRadius',
    'lg',
  )
  applyResponsiveValue(
    desktopRule,
    'borderBottomLeftRadius',
    sizeTokens,
    variantClass,
    'borderBottomLeftRadius',
    'lg',
  )
  applyResponsiveValue(
    desktopRule,
    'borderBottomRightRadius',
    sizeTokens,
    variantClass,
    'borderBottomRightRadius',
    'lg',
  )
  if (
    !hasToken(sizeTokens, 'borderTopLeftRadius') &&
    !hasToken(sizeTokens, 'borderTopRightRadius') &&
    !hasToken(sizeTokens, 'borderBottomLeftRadius') &&
    !hasToken(sizeTokens, 'borderBottomRightRadius')
  ) {
    applyResponsiveValue(desktopRule, 'borderRadius', sizeTokens, variantClass, 'borderRadius', 'lg')
  }
  if (
    hasToken(sizeTokens, 'shadowX') &&
    hasToken(sizeTokens, 'shadowY') &&
    hasToken(sizeTokens, 'shadowBlur')
  ) {
    desktopRule.boxShadow = `${vw(`var(--${variantClass}-shadow-x)`, 'lg')} ${vw(`var(--${variantClass}-shadow-y)`, 'lg')} ${vw(`var(--${variantClass}-shadow-blur)`, 'lg')} 0px var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`
  }
  if (Object.keys(desktopRule).length > 0) {
    variantRule['@screen md'] = desktopRule
  }

  if (hasToken(normalColorTokens, 'shadow')) {
    variantRule['--tw-shadow-color'] = `var(--color-${variantClass}-normal-shadow)`
    variantRule['--tw-shadow'] = 'var(--tw-shadow-colored)'
  }
  if (hasToken(normalColorTokens, 'text')) {
    variantRule.color = `var(--color-${variantClass}-normal-text)`
  }
  if (hasToken(normalColorTokens, 'border')) {
    variantRule.borderColor = `var(--color-${variantClass}-normal-border)`
  }
  if (hasToken(normalColorTokens, 'background')) {
    variantRule.backgroundColor = `var(--color-${variantClass}-normal-background)`
  }

  acc[variantSelector] = variantRule

  const hoverRule = {}
  if (hasToken(hoverColorTokens, 'shadow')) {
    hoverRule['--tw-shadow-color'] = `var(--color-${variantClass}-hover-shadow)`
    hoverRule['--tw-shadow'] = 'var(--tw-shadow-colored)'
  }
  if (hasToken(hoverColorTokens, 'text')) {
    hoverRule.color = `var(--color-${variantClass}-hover-text)`
  }
  if (hasToken(hoverColorTokens, 'border')) {
    hoverRule.borderColor = `var(--color-${variantClass}-hover-border)`
  }
  if (hasToken(hoverColorTokens, 'background')) {
    hoverRule.backgroundColor = `var(--color-${variantClass}-hover-background)`
  }
  if (Object.keys(hoverRule).length > 0) {
    acc[`:where(.${variantClass}:hover)`] = hoverRule
  }

  const titleRule = {
    transition: 'inherit',
  }
  if (buttonTypographyVariants.has(variantKey)) {
    titleRule[`@apply text-${variantClass}`] = {}
  }
  if (hasToken(normalColorTokens, 'text')) {
    titleRule.color = `var(--color-${variantClass}-normal-text)`
  }
  if (Object.keys(titleRule).length > 0) {
    acc[`:where(.${variantClass} .button-title)`] = titleRule
  }

  if (hasToken(sizeTokens, 'iconSize')) {
    acc[`:where(.${variantClass} .button-icon, .${variantClass} .button-icon > *)`] = {
      width: vw(`var(--${variantClass}-icon-size)`, 'sm'),
      height: vw(`var(--${variantClass}-icon-size)`, 'sm'),
      fontSize: vw(`var(--${variantClass}-icon-size)`, 'sm'),
      lineHeight: 1,
      '@screen md': {
        width: vw(`var(--${variantClass}-icon-size)`, 'lg'),
        height: vw(`var(--${variantClass}-icon-size)`, 'lg'),
        fontSize: vw(`var(--${variantClass}-icon-size)`, 'lg'),
      },
    }
  }

  const iconRule = {
    transition: 'inherit',
  }
  if (hasToken(normalColorTokens, 'icon')) {
    iconRule.color = `var(--color-${variantClass}-normal-icon)`
  }
  acc[`:where(.${variantClass} .button-icon)`] = iconRule

  if (hasToken(hoverColorTokens, 'icon')) {
    acc[`:where(.${variantClass}:hover .button-icon)`] = {
      color: `var(--color-${variantClass}-hover-icon)`,
    }
  }

  if (hasToken(hoverColorTokens, 'text')) {
    acc[`:where(.${variantClass}:hover .button-title)`] = {
      color: `var(--color-${variantClass}-hover-text)`,
    }
  }

  return acc
}, {})

const button = {
  '.button-icon': {
    flexShrink: 0,
  },
  '.button-title': {
    display: 'block',
  },
  ...buttonVariantStyles,
  '.buttons-autofill > *': {
    flexGrow: '1',
    flexShrink: '0',
    flexBasis: 'auto',
    maxWidth: '100%',
  },
  // On mobile, buttons should not shrink so wrapped rows stack instead.
  '@media (width < theme(screens.md))': {
    '.buttons-wrapper > *': {
      flexShrink: '0',
    },
  },
  // When inline-flexible contains buttons, grow to fill remaining space
  '.inline-flexible:has(.buttons-wrapper)': {
    flexGrow: '1',
  },
  // When buttons have full-width items inside a group, stretch the parent and reset forced justify
  '.inline-flexible:has(.buttons-full-width-items)': {
    alignSelf: 'stretch !important',
  },
  '.buttons-wrapper.buttons-full-width-items': {
    justifyContent: 'unset !important',
  },
  '@screen md': {
    '.buttons-wrapper.buttons-full-width-items.md\\:flex-row > .buttons-full-width-item': {
      flexGrow: '1',
      flexShrink: '1',
      flexBasis: 'auto',
      minWidth: '0',
      maxWidth: '100%',
      alignSelf: 'stretch',
    },
  },
}

module.exports = { button, buttonVariants }
