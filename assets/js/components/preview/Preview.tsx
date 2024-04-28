import React from 'react'
import { useLocales } from '../../contexts/LocaleContext'
import { Modal, Tab, Tabs } from 'react-bootstrap'

type MinHeight = string | number
type PreviewProps = {
  url: string,
  minHeight?: MinHeight
}

export function Preview ({ url, minHeight }: PreviewProps) {
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

export function PreviewInModal (props: PreviewProps) {
  return (
    <>
      <Modal.Header closeButton>
        <Modal.Title>Preview</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Preview {...props}/>
      </Modal.Body>
    </>
  )
}

export function Iframe ({ url, minHeight }: { url: string, minHeight?: MinHeight }) {
  return <iframe src={url} className="w-100" style={{ minHeight: minHeight ?? '70vh' }}></iframe>
}