import React, { PropsWithChildren, ReactNode } from 'react'
import { useLocales } from '../../../contexts/LocaleContext'
import { Tab, Tabs, TabsProps } from 'react-bootstrap'

type TranslatableFieldsProps = {
  render: ({ locale }: { locale: string }) => ReactNode,
}

export function TranslatableFields ({ render }: TranslatableFieldsProps) {
  const { locales } = useLocales()

  return (
    <>
      {locales.map(localeItem => (
        <React.Fragment key={localeItem}>
          {render({ locale: localeItem })}
        </React.Fragment>
      ))}
    </>
  )
}

type TranslatableFieldsTabsProps = TranslatableFieldsProps & TabsProps

export function TranslatableFieldsTabs ({ render, ...tabProps }: TranslatableFieldsTabsProps) {
  const { locales, locale } = useLocales()

  if (locales.length <= 1) return render({ locale })

  return (
    <Tabs {...tabProps} defaultActiveKey={locales.at(0)}>
      {locales.map(localeItem => (
        <Tab key={localeItem} eventKey={localeItem} title={localeItem.toUpperCase()}>
          {render({ locale: localeItem })}
        </Tab>
      ))}
    </Tabs>
  )
}

type FieldWithLocaleProps = { locale: string, className?: string } & PropsWithChildren

export function FieldWithLocale ({ children, locale, className }: FieldWithLocaleProps) {
  return (
    <div className={`input-group ${className || ''}`}>
      <div className="input-group-prepend">
        <div className="input-group-text h-100 rounded-0 text-uppercase" style={{ fontFamily: 'monospace' }}>
          {locale}
        </div>
      </div>
      {children}
    </div>
  )
}