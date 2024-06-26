import React, { useEffect, useState } from 'react'
import { FormErrors, FormLabel, FormRow } from '../ui/form/field'
import { Col, Form, FormControl, FormSelect, Row } from 'react-bootstrap'
import { ActionsContainer } from '../ui/form/ActionsContainer'
import { Link } from 'react-router-dom'
import { FormProvider, useFormContext, useWatch } from 'react-hook-form'
import { ExistentEmailTemplate } from '../../types'
import { ButtonWithLoading } from '../ui/ButtonWithLoading'
import { PreviewLayoutAction } from '../layout/PreviewLayoutAction.tsx'
import { useTemplateForm } from '../../hooks/templates.ts'
import { useGetLayouts } from '../../hooks/layout.ts'
import { useGetEmailApps } from '../../hooks/apps.ts'
import { TabTitleWithErrorsIndicator, TranslatableFieldsTabs } from '../ui/form/TranslatableFields.tsx'
import { ControlledCodeMirror } from '../ui/CodeMirror.tsx'
import { useGetConfigs } from '../../hooks/config.ts'
import { clsx } from 'clsx'

export function TemplateForm ({ emailTemplate }: { emailTemplate?: ExistentEmailTemplate }) {
  const { isPending, form, sendForm } = useTemplateForm(emailTemplate)
  const { register } = form
  const isEdit = !!emailTemplate

  return (
    <form onSubmit={form.handleSubmit(sendForm)}>
      <FormProvider {...form}>
        <Row>
          <Col xs={12} lg={6}>
            <EmailAppField/>
            <ConfigField/>
          </Col>
          <Col>
            <CustomLayoutField/>
            <FormRow className="mb-3" name="active">
              <FormLabel className="d-none d-sm-inline-block"/>
              <Form.Switch
                className="mt-sm-3"
                id="form_email_template_active_field"
                {...register('active')}
                label="Active"
                defaultChecked={emailTemplate?.active ?? true}
              />
              <FormErrors/>
            </FormRow>
          </Col>
        </Row>

        <TranslatableFieldsTabs
          className="mt-3"
          variant="pills"
          mountOnEnter
          unmountOnExit
          tabTitleRender={(locale) => <TabTitleWithErrorsIndicator
            locale={locale}
            fieldNames={[`subject.${locale}`, `content.${locale}`]}
          />}
          render={({ locale }) => (
            <div className="pt-4">
              <FormRow className="mb-3" name={`subject.${locale}`}>
                <FormLabel>Subject <small>({locale})</small></FormLabel>
                <FormControl {...register(`subject.${locale}`, { required: true })}/>
                <FormErrors/>
              </FormRow>
              <FormRow className="mb-3" name={`content.${locale}`}>
                <FormLabel>Content <small>({locale})</small></FormLabel>
                <ControlledCodeMirror name={`content.${locale}`} rules={{ required: true }}/>
                <FormErrors/>
              </FormRow>
            </div>
          )}
        />

      </FormProvider>

      <ActionsContainer>
        <Link to="/templates" className="btn btn-outline-secondary">Cancel</Link>
        <ButtonWithLoading type="submit" isLoading={isPending}>{isEdit ? 'Save' : 'Create'}</ButtonWithLoading>
      </ActionsContainer>
    </form>
  )
}

function LayoutPreview () {
  const layoutUuid = useWatch({ name: 'layoutUuid' })

  return (
    <PreviewLayoutAction uuid={layoutUuid} title="Layout Preview"/>
  )
}

function ConfigField () {
  const { register } = useFormContext()
  const { configs, isLoading } = useGetConfigs()

  return (
    <FormRow className="mb-3" name="configUuid">
      <FormLabel showLoader={isLoading}>Email Config</FormLabel>
      <FormSelect key={configs?.length} {...register('configUuid', { required: true })} disabled={isLoading}>
        {configs?.map(({ uuid, code }) => (
          <option key={uuid} value={uuid}>{code}</option>
        ))}
      </FormSelect>
      <FormErrors/>
    </FormRow>
  )
}

function CustomLayoutField () {
  const { layouts, isLoading } = useGetLayouts()
  const { register, setValue, clearErrors, getValues } = useFormContext()
  const [useCustomLayout, setUseCustomLayout] = useState(() => !!getValues('layoutUuid'))

  useEffect(() => {
    if (useCustomLayout) return

    setValue('layoutUuid', null)
    clearErrors('layoutUuid')
  }, [setValue, clearErrors, useCustomLayout])

  return (
    <FormRow className="mb-3" name="layoutUuid">
      <Form.Switch
        defaultChecked={useCustomLayout}
        onChange={({ target }) => setUseCustomLayout(target.checked)}
        className="mb-2"
        id="form_email_template_use_custom_layout_field"
        label="Use Custom Layout"
      />
      <div className={clsx('d-flex gap-1', !useCustomLayout && 'opacity-50')}>
        <FormSelect
          key={layouts?.length}
          {...register('layoutUuid', { required: useCustomLayout })}
          disabled={!useCustomLayout || isLoading}
        >
          <option value="">- Select -</option>
          {useCustomLayout && layouts?.map(({ uuid, description }) => (
            <option key={uuid} value={uuid}>{description}</option>
          ))}
        </FormSelect>
        <LayoutPreview/>
      </div>
      <FormErrors/>
    </FormRow>
  )
}

function EmailAppField () {
  const { register } = useFormContext()
  const { emailApps, isLoading } = useGetEmailApps()

  return (
    <FormRow className="mb-3" name="appId">
      <FormLabel showLoader={isLoading}>Email App</FormLabel>
      <FormSelect key={emailApps?.length} {...register('appId', { required: true })} disabled={isLoading}>
        {emailApps?.map(({ id, title }) => (
          <option key={id} value={id}>{title}</option>
        ))}
      </FormSelect>
      <FormErrors/>
    </FormRow>
  )
}
