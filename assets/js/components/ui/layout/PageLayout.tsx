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
  title: ReactNode,
  subtitle?: ReactNode,
  actions: ReactNode,
}

export function PageHeader ({ title, subtitle, actions }: PageHeaderType) {
  return (
    <div
      className="d-flex justify-content-between
      flex-wrap flex-md-nowrap align-items-center
      pt-3 pb-2 mb-5 border-bottom gap-2"
    >
      <h1 className="h2">{title}</h1>
      {subtitle && <small className="fs-6 text-secondary">{subtitle}</small>}
      <div className="d-flex gap-2 ms-auto">
        {actions}
      </div>
    </div>
  )
}