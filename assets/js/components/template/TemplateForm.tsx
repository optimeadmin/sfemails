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
import { TranslatableFieldsTabs } from '../ui/form/TranslatableFields.tsx'
import { ControlledCodeMirror } from '../ui/CodeMirror.tsx'
import { useGetConfigs } from '../../hooks/config.ts'

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
                defaultChecked
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
  const { configs } = useGetConfigs()

  return (
    <FormRow className="mb-3" name="configUuid">
      <FormLabel>Email Config</FormLabel>
      <FormSelect {...register('configUuid', { required: true })}>
        {configs?.map(({ uuid, code }) => (
          <option key={uuid} value={uuid}>{code}</option>
        ))}
      </FormSelect>
      <FormErrors/>
    </FormRow>
  )
}

function CustomLayoutField () {
  const { layouts, isLoading: isLoadingLayouts } = useGetLayouts()
  const { register, setValue, clearErrors } = useFormContext()
  const [useCustomLayout, setUseCustomLayout] = useState(false)

  useEffect(() => {
    if (useCustomLayout) return

    setValue('layoutUuid', null)
    clearErrors('layoutUuid')
  }, [setValue, clearErrors, useCustomLayout])

  return (
    <FormRow className="mb-3" name="layoutUuid">
      <Form.Switch
        onChange={({ target }) => setUseCustomLayout(target.checked)}
        className="mb-2"
        id="form_email_template_use_custom_layout_field"
        label="Use Custom Layout"
      />
      <div className={`d-flex gap-1 ${!useCustomLayout ? 'opacity-50' : ''}`}>
        <FormSelect {...register('layoutUuid', { required: useCustomLayout })} disabled={!useCustomLayout}>
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
  const { emailApps } = useGetEmailApps()

  return (
    <FormRow className="mb-3" name="appId">
      <FormLabel>Email App</FormLabel>
      <FormSelect {...register('appId', { required: true })}>
        {emailApps?.map(({ id, title }) => (
          <option key={id} value={id}>{title}</option>
        ))}
      </FormSelect>
      <FormErrors/>
    </FormRow>
  )
}
