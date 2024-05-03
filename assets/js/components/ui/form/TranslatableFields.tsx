import React, { PropsWithChildren, ReactNode } from 'react'
import { useLocales } from '../../../contexts/LocaleContext'
import { Tab, Tabs, TabsProps } from 'react-bootstrap'
import { clsx } from 'clsx'
import { get, useFormContext } from 'react-hook-form'

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

type TranslatableFieldsTabsProps = TranslatableFieldsProps & TabsProps & {
  tabTitleRender?: (locale: string) => ReactNode,
}

export function TranslatableFieldsTabs ({ render, tabTitleRender, ...tabProps }: TranslatableFieldsTabsProps) {
  const { locales, locale } = useLocales()

  if (locales.length <= 1) return render({ locale })

  let renderTitle = tabTitleRender ?? ((locale) => locale.toUpperCase())

  return (
    <Tabs {...tabProps} defaultActiveKey={locales.at(0)}>
      {locales.map(localeItem => (
        <Tab key={localeItem} eventKey={localeItem} title={renderTitle(localeItem)}>
          {render({ locale: localeItem })}
        </Tab>
      ))}
    </Tabs>
  )
}

type FieldWithLocaleProps = { locale: string, className?: string } & PropsWithChildren

export function FieldWithLocale ({ children, locale, className }: FieldWithLocaleProps) {
  return (
    <div className={clsx('input-group', className)}>
      <div className="input-group-prepend">
        <div className="input-group-text h-100 rounded-0 text-uppercase" style={{ fontFamily: 'monospace' }}>
          {locale}
        </div>
      </div>
      {children}
    </div>
  )
}

export function TabTitleWithErrorsIndicator ({ locale, fieldNames }: { locale: string, fieldNames: string[] }) {
  const { formState: { errors }, getValues } = useFormContext()
  const hasErrors = fieldNames.some(name => Boolean(get(errors, name)))

  return (
    <span className='d-inline-block position-relative'>
      {locale.toUpperCase()}
      {hasErrors && <span className="tab-item-with-errors"/>}
    </span>
  )
}