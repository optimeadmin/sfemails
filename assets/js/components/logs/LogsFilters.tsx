import React, { PropsWithChildren, useId, useRef, useState } from 'react'
import { FormLabel, FormRow } from '../ui/form/field.tsx'
import { Accordion, Button, Col, FormCheck, FormControl, Row } from 'react-bootstrap'
import { FormProvider, useForm, useFormContext } from 'react-hook-form'
import { useGetConfigs } from '../../hooks/config.ts'
import { useGetEmailApps } from '../../hooks/apps.ts'
import { ButtonWithLoading } from '../ui/ButtonWithLoading.tsx'
import { EmailStatus } from '../../types'
import { useApps } from '../../contexts/AppsContext.tsx'
import { useQueryStringData } from '../../hooks/queryStringData.ts'

type Filters = {
  apps?: string[],
  configs?: string[],
  logId?: string,
  recipients?: string,
  sendAt?: string,
  statuses?: string[],
  subject?: string,
}

const emptyFilters: Filters = {
  apps: [],
  configs: [],
  logId: '',
  recipients: '',
  sendAt: '',
  statuses: [],
  subject: '',
}

export function LogsFilters () {
  const { setQueryData, queryData } = useQueryStringData()

  const form = useForm<Filters>({ defaultValues: async () => ({ ...emptyFilters, ...queryData }) })
  const [defaultOpenFilters] = useState(() => !!queryData && Object.keys(queryData).length > 0)
  const { appsCount } = useApps()
  const $form = useRef<HTMLFormElement | null>(null)

  function submit (data: Filters) {
    const mappedData = { ...data, recipients: data.recipients?.split('\n').filter(Boolean) ?? [] }
    setQueryData({ ...queryData, ...mappedData })
  }

  function clear () {
    form.reset(emptyFilters)
    setQueryData({ ...queryData, ...emptyFilters })
  }

  return (
    <FiltersContainer defaultOpen={defaultOpenFilters}>
      <FormProvider {...form}>
        <form ref={$form} className="mb-4" onSubmit={form.handleSubmit(submit)}>
          <Row className="border-bottom">
            <Col lg={5} className="border-end email-form-filter-checkboxs">
              <FormRow className="mb-3">
                <FormLabel>Subject</FormLabel>
                <FormControl {...form.register('subject')} />
              </FormRow>
              <Row>
                <Col xl={7}>
                  <FormRow className="mb-3">
                    <FormLabel>
                      <small className="fs-6">
                        Recipients <small className="text-muted">One by line</small>
                      </small>
                    </FormLabel>
                    <FormControl
                      as="textarea"
                      {...form.register('recipients')}
                      rows={4}
                    />
                  </FormRow>
                </Col>
                <Col xl={5}>
                  <FormRow className="mb-2">
                    <FormLabel>Send At</FormLabel>
                    <FormControl type="date" {...form.register('sendAt')}/>
                  </FormRow>
                  <FormRow className="mb-3">
                    <FormLabel>Log Id</FormLabel>
                    <FormControl {...form.register('logId')}/>
                  </FormRow>
                </Col>
              </Row>
            </Col>
            <Col className="border-end email-form-filter-checkboxs">
              <StatusField/>
            </Col>
            <Col className="border-end email-form-filter-checkboxs">
              <ConfigField/>
            </Col>
            {appsCount > 1 && (
              <Col className="border-end email-form-filter-checkboxs">
                <AppsField/>
              </Col>
            )}
            <Col className="d-flex gap-2 flex-column">
              <ButtonWithLoading type="submit" variant="dark">Search</ButtonWithLoading>
              <Button variant="outline-secondary" onClick={clear}>Clear</Button>
            </Col>
          </Row>
        </form>
      </FormProvider>
    </FiltersContainer>
  )
}

type FiltersContainerProps = PropsWithChildren & {
  defaultOpen?: boolean
}

function FiltersContainer ({ children, defaultOpen = false }: FiltersContainerProps) {
  const $conatiner = useRef<HTMLDivElement | null>(null)

  return (
    <div className="mb-4">
      <Accordion ref={$conatiner} defaultActiveKey={defaultOpen ? '0' : null}>
        <Accordion.Item eventKey={'0'}>
          <Accordion.Header><span className="fs-5">Filters</span></Accordion.Header>
          <Accordion.Body onEntered={() => {
            const $input = $conatiner.current?.querySelector('input') as HTMLInputElement
            $input.focus()
          }}>
            {children}
          </Accordion.Body>
        </Accordion.Item>
      </Accordion>
    </div>
  )
}

const statuses: { value: EmailStatus, label: string }[] = [
  { value: 'pending', label: 'Pending' },
  { value: 'send', label: 'Send' },
  { value: 'error', label: 'Error' },
  { value: 'no_template', label: 'No template' },
]

function StatusField () {
  const { register } = useFormContext()
  const id = useId()

  return (
    <FormRow className="mb-3">
      <FormLabel>Status</FormLabel>
      {statuses.map(({ value, label }) => (
        <FormCheck
          key={value}
          id={`${id}_${value}`}
          {...register('statuses')}
          label={label}
          value={value}
        />
      ))}
    </FormRow>
  )
}

function ConfigField () {
  const { register } = useFormContext()
  const { configs } = useGetConfigs()
  const id = useId()

  return (
    <FormRow className="mb-3">
      <FormLabel>Config</FormLabel>
      {configs?.map(config => (
        <FormCheck
          id={`${id}_${config.uuid}`}
          key={config.uuid}
          {...register('configs')}
          value={config.uuid}
          label={config.code}
        />
      ))}
    </FormRow>
  )
}

function AppsField () {
  const { register } = useFormContext()
  const { emailApps } = useGetEmailApps()
  const id = useId()

  return (
    <FormRow className="mb-3">
      <FormLabel>Applications</FormLabel>
      {emailApps?.map(app => (
        <FormCheck
          id={`${id}_${app.id}`}
          key={app.id}
          {...register('apps')}
          value={app.id}
          label={app.title}
        />
      ))}
    </FormRow>
  )
}