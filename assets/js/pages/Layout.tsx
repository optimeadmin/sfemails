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
      <TabHeader path={'/'} title="Email Configs"/>
      <TabHeader path={'/layouts'} title="Email Layouts"/>
      <TabHeader path={'/templates'} title="Email Templates"/>
      <TabHeader path={'/logs'} title="Email Logs"/>
    </Nav>
  )
}

function TabHeader ({ path, title }: { path: string, title: string }) {
  const { pathname } = useLocation()
  const isActive = path.length > 2 ? pathname.includes(path) : pathname === path

  return (
    <Nav.Item>
      <Nav.Link as={Link} to={path} active={isActive}>{title}</Nav.Link>
    </Nav.Item>
  )
}