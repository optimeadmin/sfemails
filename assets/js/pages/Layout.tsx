import React from 'react'
import { Link, Outlet, useLocation } from 'react-router-dom'
import { Nav, Tab } from 'react-bootstrap'

export function Layout () {
  return (
    <div>

      <Tab.Container>
        <AppTabs/>

        <Tab.Content>
          <Outlet/>
        </Tab.Content>
      </Tab.Container>
    </div>
  )
}

function AppTabs () {
  return (
    <Nav variant="tabs">
      <TabHeader
        path={'/'}
        title="Email Configs"
        resolveIsActive={path => path === '/' || path.startsWith('/config')}
      />
      <TabHeader path={'/layouts'} title="Email Layouts"/>
      <TabHeader path={'/templates'} title="Email Templates"/>
      <TabHeader path={'/logs'} title="Email Logs"/>
    </Nav>
  )
}

type TabHeaderProps = {
  path: string,
  title: string,
  resolveIsActive?: (pathname: string) => boolean,
}

function TabHeader ({ path, title, resolveIsActive }: TabHeaderProps) {
  const { pathname } = useLocation()
  let isActive: boolean

  if (resolveIsActive) {
    isActive = resolveIsActive(pathname)
  } else {
    isActive = path.length > 2 ? pathname.startsWith(path) : pathname === path
  }

  return (
    <Nav.Item>
      <Nav.Link as={Link} to={path} active={isActive}>{title}</Nav.Link>
    </Nav.Item>
  )
}