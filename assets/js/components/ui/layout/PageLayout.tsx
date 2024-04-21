import React, { PropsWithChildren, ReactNode } from 'react'

type PageLayoutType = {} & PropsWithChildren

export function PageLayout ({ children }: PageLayoutType) {
  return (
    <div className="mt-3">
      {children}
    </div>
  )
}

type PageHeaderType = {
  title: string,
  actions: ReactNode,
}

export function PageHeader ({ title, actions }: PageHeaderType) {
  return (
    <div
      className="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-5 border-bottom">
      <h1 className="h2">{title}</h1>
      <div className="d-flex gap-2">
        {actions}
      </div>
    </div>
  )
}