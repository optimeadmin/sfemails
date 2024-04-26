import React from 'react'
import { useLocales } from '../../contexts/LocaleContext'
import { Tab, Tabs } from 'react-bootstrap'

type MinHeight = string | number

export function Preview ({ url, minHeight }: { url: string, minHeight?: MinHeight }) {
  const { locale, locales } = useLocales()
  const search = `/${locale}/`
  const showTabs = locales.length > 1 && url.includes(search)

  if (!showTabs) {
    return <Iframe url={url}/>
  }

  return (
    <Tabs defaultActiveKey={locale}>
      {locales.map(localeTab => (
        <Tab key={localeTab} title={localeTab.toUpperCase()} eventKey={localeTab} mountOnEnter={false}>
          <Iframe url={url.replace(search, `/${localeTab}/`)} minHeight={minHeight}/>
        </Tab>
      ))}
    </Tabs>
  )
}

function Iframe ({ url, minHeight }: { url: string, minHeight?: MinHeight }) {
  return <iframe src={url} className="w-100" style={{ minHeight: minHeight ?? '70vh' }}></iframe>
}